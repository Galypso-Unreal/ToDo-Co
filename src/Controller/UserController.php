<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    private CacheItemPoolInterface $cachePool;

    public function __construct(CacheItemPoolInterface $cachePool)
    {
        $this->cachePool = $cachePool;
    }

    #[Route('/users/', name: 'user_list')]
    public function listAction(ManagerRegistry $managerRegistry)
    {
        $item = $this->cachePool->getItem('users_list');

        if (!$item->isHit()) {
            $users = $managerRegistry->getRepository(User::class)->findAll();
            $item->set($users);
            $this->cachePool->save($item);
        } else {
            $users = $item->get();
        }
        return $this->render('user/list.html.twig', ['users' => $users]);
    }


    #[Route('/users/create', name: 'user_create')]
    public function createAction(Request $request, UserPasswordHasherInterface $userPasswordHasher, ManagerRegistry $managerRegistry)
    {

        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $managerRegistry->getManager();
                $password = $userPasswordHasher->hashPassword($user, $user->getPassword());
                $user->setPassword($password);
                $em->persist($user);
                $em->flush();

                $this->addFlash('success', "L'utilisateur a bien été ajouté.");
                $this->cachePool->deleteItem('users_list');
                return $this->redirectToRoute('user_list');
            }
        }

        return $this->render('user/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/users/{id}/edit', name: 'user_edit')]
    public function editAction(User $user, Request $request, UserPasswordHasherInterface $userPasswordHasher, ManagerRegistry $managerRegistry)
    {
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $password = $userPasswordHasher->hashPassword($user, $user->getPassword());
                $user->setPassword($password);

                $managerRegistry->getManager()->flush();

                $this->addFlash('success', "L'utilisateur a bien été modifié");
                $this->cachePool->deleteItem('users_list');
                return $this->redirectToRoute('user_list');
            }
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }
}
