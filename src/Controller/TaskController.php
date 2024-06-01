<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    private CacheItemPoolInterface $cachePool;

    // Contruct cache system pool.
    public function __construct(CacheItemPoolInterface $cachePool)
    {
        $this->cachePool = $cachePool;
    }

    #[Route('/tasks', name: 'task_list')]
    // Get list of tasks.
    public function listAction(ManagerRegistry $managerRegistry)
    {
        $item = $this->cachePool->getItem('tasks_list');

        if (!$item->isHit()) {
            $tasks = $managerRegistry->getRepository(Task::class)->findAll();
            $item->set($tasks);
            $this->cachePool->save($item);
        } else {
            $tasks = $item->get();
        }
        return $this->render('task/list.html.twig', ['tasks' => $tasks]);
    }

    #[Route('/tasks/done', name: 'task_list_done')]
    // Get list of finished tasks.
    public function listActionDone(ManagerRegistry $managerRegistry)
    {
        $item = $this->cachePool->getItem('tasks_list_done');

        if (!$item->isHit()) {
            $tasks = $managerRegistry->getRepository(Task::class)->findBy(["isDone" => "1"]);
            $item->set($tasks);
            $this->cachePool->save($item);
        } else {
            $tasks = $item->get();
        }
        return $this->render('task/list.html.twig', ['tasks' => $tasks]);
    }


    #[Route('/tasks/create', name: 'task_create')]
    // Create a new task (form).
    public function createAction(Request $request, ManagerRegistry $managerRegistry)
    {

        $task = new Task();

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $managerRegistry->getManager();

                // Check if user exist and add this user to the task.
                if ($this->getUser()->getId()) {
                    $user_id = $this->getUser();
                    $task->setUser($user_id);
                }

                $em->persist($task);
                $em->flush();
                $this->cachePool->deleteItem('tasks_list');

                $this->addFlash('success', 'La tâche a été bien été ajoutée.');

                return $this->redirectToRoute('task_list');
            }
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/tasks/{id}/edit', name: 'task_edit')]
    // Edit task form.
    public function editAction(Task $task, Request $request, ManagerRegistry $managerRegistry)
    {

        if ($this->getUser() === $task->getUser()) {
            $form = $this->createForm(TaskType::class, $task);

            $form->handleRequest($request);

            if ($form->isSubmitted()) {
                if ($form->isValid()) {
                    $managerRegistry->getManager()->flush();

                    $this->addFlash('success', 'La tâche a bien été modifiée.');
                    $this->cachePool->deleteItem('tasks_list');
                    $this->cachePool->deleteItem('tasks_list_done');
                    return $this->redirectToRoute('task_list');
                }
            }

            return $this->render('task/edit.html.twig', [
                'form' => $form->createView(),
                'task' => $task,
            ]);
        } else {
            $this->addFlash('error', sprintf('La tâche %s ne peux pas être modifier par un autre utilisateur', $task->getTitle()));
            return $this->redirectToRoute('task_list');
        }
    }

    #[Route('/tasks/{id}/toggle', name: 'task_toggle')]
    // Toggle task to set them done or not.
    public function toggleTaskAction(Task $task, ManagerRegistry $managerRegistry)
    {
        $task->toggle(!$task->isDone());
        $managerRegistry->getManager()->flush();
        $this->cachePool->deleteItem('tasks_list');
        $this->cachePool->deleteItem('tasks_list_done');

        if ($task->isDone() == true) {
            $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));
        } else {
            $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme non faite.', $task->getTitle()));
        }

        return $this->redirectToRoute('task_list');
    }

    #[Route('/tasks/{id}/delete', name: 'task_delete')]
    // Delete a task, user need to be the same than creator or admin.
    public function deleteTaskAction(Task $task, ManagerRegistry $managerRegistry)
    {
        if ($this->getUser() === $task->getUser() || $this->getUser()->getRoles() === ['ROLE_ADMIN'] && $task->getUser()->getRoles() === ['ROLE_ANONYM']) {
            $em = $managerRegistry->getManager();
            $em->remove($task);
            $em->flush();

            $this->addFlash('success', 'La tâche a bien été supprimée.');
            $this->cachePool->deleteItem('tasks_list');
            $this->cachePool->deleteItem('tasks_list_done');
            return $this->redirectToRoute('task_list');
        } else {
            $this->addFlash('error', 'Vous ne pouvez pas supprimer la tâche d\'un autre utilisateur');
            return $this->redirectToRoute('task_list');
        }
    }
}
