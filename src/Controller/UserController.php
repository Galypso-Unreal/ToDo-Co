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

    /**
     * @var CacheItemPoolInterface $cachePool of type `CacheItemPoolInterface` within the `UserController` class.
     */
    private CacheItemPoolInterface $cachePool;


    /**
     * The function is a constructor that initializes a cache pool object.
     * 
     * @param CacheItemPoolInterface $cachePool The `cachePool` parameter in the constructor is of type
     * `CacheItemPoolInterface`. This parameter is used to inject an instance of a cache pool
     * implementation into the class. This allows the class to interact with the cache pool to store
     * and retrieve cached data.
     */
    public function __construct(CacheItemPoolInterface $cachePool)
    {
        $this->cachePool = $cachePool;

    }// End __construct().


    #[Route('/users/', name: 'user_list')]


    /**
     * The function retrieves a list of users from the cache if available, otherwise fetches it from
     * the database and stores it in the cache.
     * 
     * @param ManagerRegistry $managerRegistry interface is part of the Doctrine ORM (Object-Relational Mapping) integration.
     * It provides a way to manage and access the different Doctrine entity managers and connections.
     * The ManagerRegistry is often used for dependency injection into services or controllers where database operations are required.
     * 
     * @return Response `listAction` function returns a rendered template 'user/list.html.twig' with a
     * variable 'users' containing the list of users fetched from the cache or database using the
     * ManagerRegistry.
     */
    public function listAction(ManagerRegistry $managerRegistry)
    {
        $item = $this->cachePool->getItem('users_list');

        if ($item->isHit() === false) {
            $users = $managerRegistry->getRepository(User::class)->findAll();
            $item->set($users);
            $this->cachePool->save($item);
        } else {
            $users = $item->get();
        }

        return $this->render('user/list.html.twig', ['users' => $users]);

    }// End listAction().


    #[Route('/users/create', name: 'user_create')]


    /**
     * This PHP function creates a new user entity, hashes the user's password, persists the user in
     * the database, and redirects to the user list page if the form submission is successful.
     * 
     * @param Request $request The current HTTP request object, containing query parameters and other request data.
     * 
     * @param UserPasswordHasherInterface $userPasswordHasher The PasswordHasherInterface service, used to hash the password.
     * 
     * @param ManagerRegistry $managerRegistry interface is part of the Doctrine ORM (Object-Relational Mapping) integration.
     * It provides a way to manage and access the different Doctrine entity managers and connections.
     * The ManagerRegistry is often used for dependency injection into services or controllers where database operations are required.
     * 
     * @return Response `createAction` function returns a response that renders the `user/create.html.twig`
     * template with the form view if the form has not been submitted or is not valid. If the form is
     * submitted and valid, it adds the user to the database, flashes a success message, deletes an
     * item from the cache, and redirects to the `user_list` route.
     */
    public function createAction(Request $request, UserPasswordHasherInterface $userPasswordHasher, ManagerRegistry $managerRegistry)
    {

        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() === true) {
            if ($form->isValid() === true) {
                $entityManager = $managerRegistry->getManager();
                $password = $userPasswordHasher->hashPassword($user, $user->getPassword());
                $user->setPassword($password);
                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', "L'utilisateur a bien été ajouté.");
                $this->cachePool->deleteItem('users_list');
                return $this->redirectToRoute('user_list');
            }
        }

        return $this->render('user/create.html.twig', ['form' => $form->createView()]);

    }// End createAction().


    #[Route('/users/{id}/edit', name: 'user_edit')]


    /**
     * This PHP function edits a user entity, hashes the password, updates the user in the database,
     * and redirects to the user list page with a success message.
     * 
     * @param User $user The User entity to be updated with the news datas in form.
     * 
     * @param Request $request The current HTTP request object, containing query parameters and other request data.
     * 
     * @param UserPasswordHasherInterface $userPasswordHasher The PasswordHasherInterface service, used to hash the password.
     * 
     * @param ManagerRegistry $managerRegistry interface is part of the Doctrine ORM (Object-Relational Mapping) integration.
     * It provides a way to manage and access the different Doctrine entity managers and connections.
     * The ManagerRegistry is often used for dependency injection into services or controllers where database operations are required.
     * 
     * @return Response function `editAction` is returning a rendered template for the `user/edit.html.twig`
     * view with the form and user data passed to it. If the form is submitted and valid, it will hash
     * the user's password, update the user entity in the database, add a success flash message, delete
     * an item from the cache, and then redirect to the `user_list` route.
     */
    public function editAction(User $user, Request $request, UserPasswordHasherInterface $userPasswordHasher, ManagerRegistry $managerRegistry)
    {
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() === true) {
            if ($form->isValid() === true) {
                $password = $userPasswordHasher->hashPassword($user, $user->getPassword());
                $user->setPassword($password);

                $managerRegistry->getManager()->flush();

                $this->addFlash('success', "L'utilisateur a bien été modifié");
                $this->cachePool->deleteItem('users_list');
                return $this->redirectToRoute('user_list');
            }
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
        
    }// End editAction().

    
}
