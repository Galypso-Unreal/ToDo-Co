<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskTest extends KernelTestCase
{


    /**
     * @var `$entityManager` within the `TaskTest` class. This property is used create entitymanager
     * symfony for database interaction like persist, flush, ect.
     */
    private ?EntityManagerInterface $entityManager = null;


    /**
     * Get doctrine for managing entities.
     */
    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

    }// End setUp().


    /**
     * Test if construct work.
     */
    public function testConstruct(): void
    {
        $task = new Task();
        $this->assertEquals(false, $task->isDone());
        $this->assertNotNull($task);

    }// End testConstruct().


    /**
     * Test if get id function work.
     */
    public function testId(): void
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => 'anonymous']);

        // Create new task.
        $task = new Task();
        $task->setTitle('Test Task');
        $task->setContent('This is a test task description.');
        $task->setUser($user);

        // Save task in database.
        $this->entityManager->persist($task);
        $this->entityManager->flush();

        // Get task from database.
        $taskRepository = $this->entityManager->getRepository(Task::class);
        $savedTask = $taskRepository->find($task->getId());

        // Check if id is correct.
        $this->assertNotNull($savedTask);
        $this->assertIsInt($savedTask->getId());

    }// End testId().


    /**
     * Test if created at work.
     */
    public function testCreatedAt(): void
    {
        $task = new Task();
        $date = new DateTime('2022-07-03 04:53:53');
        $task->setCreatedAt($date);
        $this->assertEquals(new DateTime('2022-07-03 04:53:53'), $task->getCreatedAt());

    }// End testCreatedAt().


    /**
     * Test set title.
     */
    public function testTitle(): void
    {
        $task = new Task();
        $task->setTitle('here');
        $this->assertEquals('here', $task->getTitle());

    }// End testTitle().


    /**
     * Test set content.
     */
    public function testContent(): void
    {
        $task = new Task();
        $task->setContent('testContent');
        $this->assertEquals('testContent', $task->getContent());

    }// End testContent().


    /**
     * Test isDone function work.
     */
    public function testIsDone(): void
    {
        $task = new Task();
        $task->toggle(true);
        $this->assertEquals(true, $task->isDone());

    }// End testIsDone().


    /**
     * Test linked user to a task.
     */
    public function testUser(): void
    {
        $user = new User();
        $user->setUsername('john');
        $user->setEmail('john@gmail.com');
        $user->setPassword('pass');

        $task = new Task();
        $task->setUser($user);
        $this->assertEquals($user, $task->getUser());
        
    }// End testUser().

    
}
