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

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    function testConstruct(): void
    {
        $task = new Task();
        $this->assertEquals(false, $task->isDone());
        $this->assertNotNull($task);
    }

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

    function testCreatedAt(): void
    {
        $task = new Task();
        $date = new DateTime('2022-07-03 04:53:53');
        $task->setCreatedAt($date);
        $this->assertEquals(new DateTime('2022-07-03 04:53:53'), $task->getCreatedAt());
    }

    function testTitle(): void
    {
        $task = new Task();
        $task->setTitle('here');
        $this->assertEquals('here', $task->getTitle());
    }

    function testContent(): void
    {
        $task = new Task();
        $task->setContent('testContent');
        $this->assertEquals('testContent', $task->getContent());
    }

    function testIsDone(): void
    {
        $task = new Task();
        $task->toggle(true);
        $this->assertEquals(true, $task->isDone());
    }

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
