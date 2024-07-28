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

    /**
     * @var CacheItemPoolInterface $cachePool private property of type `CacheItemPoolInterface`. This property is then
     * initialized in the constructor of the `TaskController` class using dependency injection.
     */
    private CacheItemPoolInterface $cachePool;


    /**
     * The function is a constructor that initializes a cache pool object.
     * 
     * @param CacheItemPoolInterface $cachePool this init cache functions like : getitem, remove, ect.
     * 
     * @return void function return cache pool init from __construct function.
     */
    public function __construct(CacheItemPoolInterface $cachePool)
    {
        $this->cachePool = $cachePool;

    } // End __construct().


    #[Route('/tasks', name: 'task_list')]


    /**
     * The listAction function retrieves a list of tasks from the cache if available, otherwise fetches
     * them from the database and stores them in the cache.
     * 
     * @param ManagerRegistry $managerRegistry used for use function of managerRegistry like getRepository.
     * Need to getRepository to queryable tasks.
     * 
     * @return Response `listAction` function returns a rendered template 'task/list.html.twig' with an
     * array of tasks passed as a parameter.
     */
    public function listAction(ManagerRegistry $managerRegistry)
    {
        $item = $this->cachePool->getItem('tasks_list');

        if ($item->isHit() === false) {
            $tasks = $managerRegistry->getRepository(Task::class)->findAll();
            $item->set($tasks);
            $this->cachePool->save($item);
        } else {
            $tasks = $item->get();
        }

        return $this->render('task/list.html.twig', ['tasks' => $tasks]);

    } // End listAction().


    #[Route('/tasks/done', name: 'task_list_done')]


    /**
     * This PHP function retrieves a list of tasks marked as done from the cache or database and
     * renders them in a Twig template.
     * 
     * @param ManagerRegistry $managerRegistry used for use function of managerRegistry like getRepository.
     * Need to getRepository to queryable tasks.
     * 
     * @return `listActionDone` function is returning a rendered template 'task/list.html.twig'
     * with an array of tasks that are marked as done. The tasks are retrieved from the cache if
     * available, otherwise they are fetched from the database using the `ManagerRegistry` and stored
     * in the cache for future use.
     */
    public function listActionDone(ManagerRegistry $managerRegistry)
    {
        $item = $this->cachePool->getItem('tasks_list_done');

        if ($item->isHit() === false) {
            $tasks = $managerRegistry->getRepository(Task::class)->findBy(["isDone" => "1"]);
            $item->set($tasks);
            $this->cachePool->save($item);
        } else {
            $tasks = $item->get();
        }

        return $this->render('task/list.html.twig', ['tasks' => $tasks]);

    } // End listActionDone().


    #[Route('/tasks/create', name: 'task_create')]


    /**
     * The function `createAction` creates a new task, associates it with a user if the user exists,
     * persists it to the database, and redirects to the task list page with a success message.
     * 
     * @param Request $request Request HTTP user for $_POST, $_GETn cookies ect.
     *                         This is necessary to get all data from post action.
     * 
     * @param ManagerRegistry $managerRegistry Used to push data to the database.
     *                                         After summited the form, all data is stocked to the database in table Task.
     * 
     * @return Response CreateAction function returns a response that renders the `task/create.html.twig`
     *                  template with the form view if the form has not been submitted or is not valid. If the form is
     *                  submitted and valid, it persists the task entity, adds the user to the task if the user exists,
     *                  flushes the entity manager, deletes an item from the cache pool, adds a success flash message,
     */
    public function createAction(Request $request, ManagerRegistry $managerRegistry)
    {

        $task = new Task();

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() === true) {
            if ($form->isValid() === true) {
                $entityManager = $managerRegistry->getManager();

                // Check if user exist and add this user to the task.
                if (empty($this->getUser()->getId()) === false) {
                    $user_id = $this->getUser();
                    $task->setUser($user_id);
                }

                $entityManager->persist($task);
                $entityManager->flush();
                $this->cachePool->deleteItem('tasks_list');

                $this->addFlash('success', 'La tâche a été bien été ajoutée.');

                return $this->redirectToRoute('task_list');
            }
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);

    } // End createAction().


    #[Route('/tasks/{id}/edit', name: 'task_edit')]


    /**
     * This PHP function edits a task if the current user is the owner, otherwise it displays an error
     * message.
     * 
     * @param Task $task This is the entity Task.
     *                   It's used to modify Task entity in database with new data from form.
     * 
     * @param Request $request Request HTTP user for $_POST, $_GETn cookies ect.
     *                         This is necessary to get all data from post action.
     * 
     * @param ManagerRegistry $managerRegistry Used to push data to the database.
     *                                         After summited the form, all data is stocked to the database in table Task.
     * 
     * @return Response The user editing the task is the same as the task's user, the function will return
     *                  the rendered template for editing the task with the form and task data. If the form is submitted
     *                  and valid, the task will be updated in the database, a success flash message will be added, and
     *                  the cache items for task lists will be deleted before redirecting to the task list page.
     */
    public function editAction(Task $task, Request $request, ManagerRegistry $managerRegistry)
    {

        if ($this->getUser() === $task->getUser() || ($this->getUser()->getRoles() === ['ROLE_ADMIN'] && $task->getUser()->getRoles() === ['ROLE_ANONYM'])) {
            $form = $this->createForm(TaskType::class, $task);

            $form->handleRequest($request);

            if ($form->isSubmitted() === true) {
                if ($form->isValid() === true) {
                    $managerRegistry->getManager()->flush();

                    $this->addFlash('success', 'La tâche a bien été modifiée.');
                    $this->cachePool->deleteItem('tasks_list');
                    $this->cachePool->deleteItem('tasks_list_done');
                    return $this->redirectToRoute('task_list');
                }
            }

            return $this->render(
                'task/edit.html.twig',
                [
                    'form' => $form->createView(),
                    'task' => $task,
                ]
            );
        }//end if

        $this->addFlash('error', sprintf('La tâche %s ne peux pas être modifier par un autre utilisateur', $task->getTitle()));
        return $this->redirectToRoute('task_list');

    } // End editAction().


    #[Route('/tasks/{id}/toggle', name: 'task_toggle')]


    /**
     * The function toggles the status of a task between done and not done, updates the cache, displays
     * a success message, and redirects to the task list page.
     * 
     * @param Task $task This is the entity Task.
     *                   It's used to modify Task entity in database with new data (here get the toggle true/false).
     * 
     * @param ManagerRegistry $managerRegistry Used to push data to the database.
     *                                         After summited the form, all data is stocked to the database in table Task.
     * 
     * @return Response redirection to the route named 'task_list'.
     */
    public function toggleTaskAction(Task $task, ManagerRegistry $managerRegistry)
    {
        $task->toggle(!$task->isDone());
        $managerRegistry->getManager()->flush();
        $this->cachePool->deleteItem('tasks_list');
        $this->cachePool->deleteItem('tasks_list_done');

        if ($task->isDone() === true) {
            $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));
        } else {
            $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme non faite.', $task->getTitle()));
        }

        return $this->redirectToRoute('task_list');

    } // End toggleTaskAction().


    #[Route('/tasks/{id}/delete', name: 'task_delete')]


    /**
     * The function `deleteTaskAction` deletes a task if the current user is the owner of the task or
     * has admin role and the task belongs to an anonymous user, otherwise it displays an error
     * message.
     * 
     * @param Task $task This is the entity Task.
     *                   It's used to delete Task entity in database.
     * 
     * @param ManagerRegistry $managerRegistry Used to push data to the database.
     *                                         After summited the form, all data is stocked to the database in table Task.
     * 
     * @return Response Function `deleteTaskAction` is returning a redirection to the route named
     *                  'task_list' after deleting a task if the conditions are met. If the conditions are not met, it
     *                  returns a redirection to the route 'task_list' with an error flash message.
     */
    public function deleteTaskAction(Task $task, ManagerRegistry $managerRegistry)
    {
        if ($this->getUser() === $task->getUser() || $this->getUser()->getRoles() === ['ROLE_ADMIN'] && $task->getUser()->getRoles() === ['ROLE_ANONYM']) {
            $entityManager = $managerRegistry->getManager();
            $entityManager->remove($task);
            $entityManager->flush();

            $this->addFlash('success', 'La tâche a bien été supprimée.');
            $this->cachePool->deleteItem('tasks_list');
            $this->cachePool->deleteItem('tasks_list_done');
            return $this->redirectToRoute('task_list');
        } else {
            $this->addFlash('error', 'Vous ne pouvez pas supprimer la tâche d\'un autre utilisateur');
            return $this->redirectToRoute('task_list');
        }

    } // End deleteTaskAction().


}
