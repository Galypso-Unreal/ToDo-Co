<?php 
namespace AppBundle\DataFixtures;

use App\Entity\Product;
use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager as PersistenceObjectManager;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AppFixtures extends Fixture
{
    protected $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(PersistenceObjectManager $manager)
    {

        $passwordEncoder = $this->container->get('security.password_encoder');

        // Create users
        $user_anonym = new User();
        $user_anonym->setUsername('AnonymeUser');
        $user_anonym->setEmail('anonym@todo.com');
        $user_anonym->setPassword($passwordEncoder->encodePassword($user_anonym, 'anonym'));
        $user_anonym->setRoles(['ROLE_ANONYM']);

        $user_user = new User();
        $user_user->setUsername('User');
        $user_user->setPassword($passwordEncoder->encodePassword($user_user, 'user'));
        $user_user->setEmail('user@todo.com');
        $user_user->setRoles(['ROLE_USER']);

        $user_admin = new User();
        $user_admin->setUsername('Admin');
        $user_admin->setPassword($passwordEncoder->encodePassword($user_admin, 'admin'));
        $user_admin->setEmail('admin@todo.com');
        $user_admin->setRoles(['ROLE_ADMIN']);

        $manager->persist($user_anonym);
        $manager->persist($user_user);
        $manager->persist($user_admin);
        
        // Create Tasks
        $task1 = new Task();
        $task1->setTitle('Tâche User');
        $task1->setContent('Contenu de la tâche User');
        $task1->setUser($user_user); // User for task
        $manager->persist($task1);
        
        $task2 = new Task();
        $task2->setTitle('Tâche Admin');
        $task2->setContent('Contenu de la tâche Admin');
        $task2->setUser($user_admin);
        $manager->persist($task2);

        for ($i=0; $i < 10; $i++) { 
            $task = new Task();
            $task->setTitle('Tâche '.$i);
            $task->setContent('Contenu de la tâche '.$i);
            $task->setUser($user_anonym);
            $manager->persist($task);
        }
        
        
        $manager->flush();
    }
}