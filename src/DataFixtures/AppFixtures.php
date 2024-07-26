<?php

namespace App\DataFixtures;


use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @codeCoverageIgnore
 */
class AppFixtures extends Fixture
{
    
    /**
     * @var $container in the `AppFixtures` class. key part of the framework's Dependency Injection (DI) mechanism.
     * It holds an instance of the service container,
     * which is responsible for managing the lifecycle of services and their dependencies within a Symfony application. 
     */
    protected $container;

    /**
     * @var $passwordEncoder in the `AppFixtures` class. This property is used to store an instance of the
     * `UserPasswordHasherInterface` object, which is responsible for hashing passwords in Symfony
     * applications. This property is initialized in the constructor of the class using dependency
     * injection to ensure that the `UserPasswordHasherInterface` object is available for use
     * throughout the class methods. 
     */
    private $passwordEncoder;


    /**
     * The function `setContainer` sets the container property of an object to a specified value.
     * 
     * @param ContainerInterface $container The Symfony service container instance.
     */
    public function setContainer(ContainerInterface $container = null): void
    {
        $this->container = $container;

    }// End setContainer().


    /**
     * The constructor function initializes the password encoder with a UserPasswordHasherInterface
     * object.
     * 
     * @param UserPasswordHasherInterface $userPasswordHasher The password interface for hashing user password.
     */
    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->passwordEncoder = $userPasswordHasher;

    }// End __construct().


    /**
     * The function creates users with different roles and tasks assigned to them in a PHP application.
     * 
     * @param ObjectManager $manager The Doctrine ObjectManager instance.
     */
    public function load(ObjectManager $manager): void
    {


        // Create users.

        $user_user = new User();
        $user_user->setUsername('User');
        $user_user->setPassword($this->passwordEncoder->hashPassword($user_user, 'user'));
        $user_user->setEmail('user@todo.com');
        $user_user->setRoles(['ROLE_USER']);

        $user_admin = new User();
        $user_admin->setUsername('Admin');
        $user_admin->setPassword($this->passwordEncoder->hashPassword($user_admin, 'admin'));
        $user_admin->setEmail('admin@todo.com');
        $user_admin->setRoles(['ROLE_ADMIN']);

        $manager->persist($user_user);
        $manager->persist($user_admin);

        // Create Tasks.
        $task1 = new Task();
        $task1->setTitle('Tâche User');
        $task1->setContent('Contenu de la tâche User');
        // User for task.
        $task1->setUser($user_user);
        $manager->persist($task1);

        $task2 = new Task();
        $task2->setTitle('Tâche Admin');
        $task2->setContent('Contenu de la tâche Admin');
        $task2->setUser($user_admin);
        $manager->persist($task2);

        for ($i = 0; $i < 10; $i++) {
            $task = new Task();
            $task->setTitle('Tâche '.$i);
            $task->setContent('Contenu de la tâche '.$i);
            $task->setUser(null);
            $manager->persist($task);
        }


        $manager->flush();
        
    }// End load().


}
