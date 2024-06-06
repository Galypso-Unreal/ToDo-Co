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
    protected $container;

    /**
     * The function `setContainer` sets the container property of an object to a specified value.
     * 
     * @param ContainerInterface
     */
    public function setContainer(ContainerInterface $container = null): void
    {
        $this->container = $container;
    }
    //end setContainer()

    private $passwordEncoder;
    
    /**
     * The constructor function initializes the password encoder with a UserPasswordHasherInterface
     * object.
     * 
     * @param UserPasswordHasherInterface
     */
    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->passwordEncoder = $userPasswordHasher;
    }
    //end __construct()

    /**
     * The function creates users with different roles and tasks assigned to them in a PHP application.
     * 
     * @param ObjectManager
     */
    public function load(ObjectManager $manager): void
    {


        // Create users.
        $user_anonym = new User();
        $user_anonym->setUsername('AnonymeUser');
        $user_anonym->setEmail('anonym@todo.com');
        $user_anonym->setPassword($this->passwordEncoder->hashPassword($user_anonym, 'anonym'));
        $user_anonym->setRoles(['ROLE_ANONYM']);

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

        $manager->persist($user_anonym);
        $manager->persist($user_user);
        $manager->persist($user_admin);

        // Create Tasks.
        $task1 = new Task();
        $task1->setTitle('Tâche User');
        $task1->setContent('Contenu de la tâche User');
        $task1->setUser($user_user); // User for task.
        $manager->persist($task1);

        $task2 = new Task();
        $task2->setTitle('Tâche Admin');
        $task2->setContent('Contenu de la tâche Admin');
        $task2->setUser($user_admin);
        $manager->persist($task2);

        for ($i = 0; $i < 10; $i++) {
            $task = new Task();
            $task->setTitle('Tâche ' . $i);
            $task->setContent('Contenu de la tâche ' . $i);
            $task->setUser($user_anonym);
            $manager->persist($task);
        }


        $manager->flush();
    }
    //end load()
}
