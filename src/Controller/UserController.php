<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    #[Route('/users/', name: 'user_list')]
    public function listAction(ManagerRegistry $managerRegistry)
    {
        if ($this->isGranted('ROLE_ADMIN') === true) {
            return $this->render('user/list.html.twig', ['users' => $managerRegistry->getRepository(User::class)->findAll()]);
        } else {
            return $this->redirectToRoute("homepage");
        }
    }


    #[Route('/users/create', name: 'user_create')]
    public function createAction(Request $request, UserPasswordHasherInterface $userPasswordHasher, ManagerRegistry $managerRegistry)
    {
        if ($this->isGranted('ROLE_ADMIN') === true) {
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

                    return $this->redirectToRoute('user_list');
                }
            }

            return $this->render('user/create.html.twig', ['form' => $form->createView()]);
        } else {
            return $this->redirectToRoute("homepage");
        }
    }

    #[Route('/users/{id}/edit', name: 'user_edit')]
    public function editAction(User $user, Request $request, UserPasswordHasherInterface $userPasswordHasher, ManagerRegistry $managerRegistry)
    {
        if ($this->isGranted('ROLE_ADMIN') === true) {
            $form = $this->createForm(UserType::class, $user);

            $form->handleRequest($request);

            if ($form->isSubmitted()) {
                if ($form->isValid()) {
                    $password = $userPasswordHasher->hashPassword($user, $user->getPassword());
                    $user->setPassword($password);

                    $managerRegistry->getManager()->flush();

                    $this->addFlash('success', "L'utilisateur a bien été modifié");

                    return $this->redirectToRoute('user_list');
                }
            }

            return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
        } else {
            return $this->redirectToRoute("homepage");
        }
    }
}
