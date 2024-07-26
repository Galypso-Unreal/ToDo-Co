<?php
namespace App\Tests\Command;

use App\Command\UpdateTasksCommand;
use App\Entity\Task;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UpdateTasksCommandTest extends KernelTestCase
{

    /**
     * @var EntityManagerInterface $entityManager of type `EntityManagerInterface` within the `UpdateTasksCommandTest` class need to defined to access passwordHasher.
     */
    private ?EntityManagerInterface $entityManager = null;

    /**
     * @var UserPasswordHasherInterface $passwordHasher of type `UserPasswordHasherInterface` within the `UpdateTasksCommandTest` class need to defined to access passwordHasher.
     */
    private ?UserPasswordHasherInterface $passwordHasher = null;


    /**
     * SetUp current test for access to EntityManager and PasswordHasher.
     */
    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $container = $kernel->getContainer();

        // Get EntityManager and UserPasswordHasher from container.
        $this->entityManager = $container
            ->get('doctrine')
            ->getManager();
            $this->passwordHasher = static::getContainer()->get(UserPasswordHasherInterface::class);
    }


    /**
     * Test to create anonymousUser if no id is submit.
     */
    public function testCreateAnonymousUserWhenNoneExists(): void
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => 'anonymous']);
        // Get all tasks linked to anonym user.
        $tasks = $this->entityManager->getRepository(Task::class)->findBy(['user' => $user]);

        if (empty($tasks) === false && empty($user) === false) {
            // Delete all tasks of anonym user.
            foreach ($tasks as $task) {
                $this->entityManager->remove($task);
            }

            $this->entityManager->remove($user);
            $this->entityManager->flush();
        }

        $command = new UpdateTasksCommand($this->entityManager, $this->passwordHasher);
        $input = new ArrayInput([]);
        $output = new BufferedOutput();
        
        $statusCode = $command->run($input, $output);

        $this->assertEquals(Command::SUCCESS, $statusCode);

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => 'anonymous']);
        $this->assertNotNull($user, 'L\'utilisateur anonyme devrait être créé.');
        $this->assertEquals('anonymous@anonym.com', $user->getEmail());
        $this->assertEquals(['ROLE_ANONYM'], $user->getRoles());
        $this->assertTrue(strlen($user->getPassword()) > 0, 'Le mot de passe devrait être haché.');
    }


    /**
     * Test to user exisiting anonymous user.
     */
    public function testUseExistingAnonymousUser(): void
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => 'anonymous']);

        $command = new UpdateTasksCommand($this->entityManager, $this->passwordHasher);
        $input = new ArrayInput(['anonymousUserId' => $user->getId()]);
        $output = new BufferedOutput();
        
        $statusCode = $command->run($input, $output);

        $this->assertEquals(Command::SUCCESS, $statusCode);
    }

    
    /**
     * Test if anonymous user is already exists.
     */
    public function testErrorIfAnonymousUserAlreadyExists(): void
    {
        $command = new UpdateTasksCommand($this->entityManager, $this->passwordHasher);
        $input = new ArrayInput([]); // no id.
        $output = new BufferedOutput();
        
        $statusCode = $command->run($input, $output);

        $this->assertEquals(Command::INVALID, $statusCode);
        $this->assertStringContainsString('Un utilisateur anonyme est déjà présent ID :', $output->fetch());
    }


    /**
     * Test to update task and asigned to anonymous user.
     */
    public function testUpdateTasksWithAnonymousUser(): void
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => 'anonymous']);

        $task = new Task();
        $task->setTitle('testTaskUpdateAnonym');
        $task->setContent('testTaskUpdateAnonymContent');
        $this->entityManager->persist($task);
        $this->entityManager->flush();

        $command = new UpdateTasksCommand($this->entityManager, $this->passwordHasher);
        $input = new ArrayInput(['anonymousUserId' => $user->getId()]);
        $output = new BufferedOutput();
        
        $statusCode = $command->run($input, $output);

        $this->assertEquals(Command::SUCCESS, $statusCode);

        $task = $this->entityManager->getRepository(Task::class)->findOneBy(['id' => $task->getId()]);

        $this->assertSame($user, $task->getUser());

    }


    /**
     * Test if no tasks need to be updated.
     */
    public function testNoTasksToUpdate(): void
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => 'anonymous']);

        $command = new UpdateTasksCommand($this->entityManager, $this->passwordHasher);
        $input = new ArrayInput(['anonymousUserId' => $user->getId()]);
        $output = new BufferedOutput();
        
        $statusCode = $command->run($input, $output);

        $this->assertEquals(Command::SUCCESS, $statusCode);
        $this->assertStringContainsString('Aucune tâche sans utilisateur trouvé.', $output->fetch());
    }


    /**
     * Test if anonymous user is not find.
     */
    public function testNoAnonymousUserFind(): void
    {
        $command = new UpdateTasksCommand($this->entityManager, $this->passwordHasher);
        $input = new ArrayInput(['anonymousUserId' => "0"]);
        $output = new BufferedOutput();
        
        $statusCode = $command->run($input, $output);

        $this->assertEquals(Command::FAILURE, $statusCode);
        $this->assertStringContainsString('Utilisateur anonyme non trouvé.', $output->fetch());
    }
}
