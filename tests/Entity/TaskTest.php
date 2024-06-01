<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskTest extends KernelTestCase
{

    private ?EntityManagerInterface $entityManager = null;

    // Get doctrine for managing entities.
    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    // Test if construct work.
    function testConstruct(): void
    {
        $task = new Task();
        $this->assertEquals(false, $task->isDone());
        $this->assertNotNull($task);
    }

    // Test if get id function work.
    function testId(): void
    {
        // Create new task
        $task = new Task();
        $task->setTitle('Test Task');
        $task->setContent('This is a test task description.');

        // Save task in database
        $this->entityManager->persist($task);
        $this->entityManager->flush();

        // Get task from database
        $taskRepository = $this->entityManager->getRepository(Task::class);
        $savedTask = $taskRepository->find($task->getId());

        // Check if id is correct
        $this->assertNotNull($savedTask);
        $this->assertIsInt($savedTask->getId());
    }

    // Test if created at work.
    function testCreatedAt(): void
    {
        $task = new Task();
        $date = new DateTime('2022-07-03 04:53:53');
        $task->setCreatedAt($date);
        $this->assertEquals(new DateTime('2022-07-03 04:53:53'), $task->getCreatedAt());
    }

    // Test set title.
    function testTitle(): void
    {
        $task = new Task();
        $task->setTitle('here');
        $this->assertEquals('here', $task->getTitle());
    }

    // Test set content.
    function testContent(): void
    {
        $task = new Task();
        $task->setContent('testContent');
        $this->assertEquals('testContent', $task->getContent());
    }

    // Test isDone function work.
    function testIsDone(): void
    {
        $task = new Task();
        $task->toggle(true);
        $this->assertEquals(true, $task->isDone());
    }

    // Test linked user to a task.
    function testUser(): void
    {
        $user = new User();
        $user->setUsername('john');
        $user->setEmail('john@gmail.com');
        $user->setPassword('pass');

        $task = new Task();
        $task->setUser($user);
        $this->assertEquals($user, $task->getUser());
    }
}
