<?php


namespace App\Tests\Entity;

use App\Entity\User;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskRepositoryTest extends KernelTestCase
{

    // Test if tasksByUser function get one task by user.
    function testFindTasksByUser(): void
    {

        $userRepository = static::getContainer()->get(UserRepository::class);

        $taskRepository = static::getContainer()->get(TaskRepository::class);

        // Retrieve the test user.
        $testUser = $userRepository->findOneBy(['username' => 'User']);

        $tasks = $taskRepository->findTasksByUser($testUser->getId());

        $this->assertNotNull($tasks);
        
    }// End testFindTasksByUser().
}
