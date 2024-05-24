<?php

namespace App\Tests;

use App\DataFixtures\AppFixtures;
use App\Entity\Task;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

// class AppFixturesTest extends KernelTestCase
// {
//     private EntityManagerInterface $entityManager;
//     private ?UserPasswordHasherInterface $passwordHasher = null;

//     protected function setUp(): void
//     {
//         // Boot the Symfony kernel
//         self::bootKernel();

//         // Get the entity manager
//         $this->entityManager = static::getContainer()->get('doctrine')->getManager();

//         // Clear the entity manager to ensure clean state
//         $this->entityManager->clear();

//         $this->passwordHasher = static::getContainer()->get(UserPasswordHasherInterface::class);
//     }

//     public function testLoadFixtures(): void
//     {
//         // Instantiate your fixtures class
//         $fixtures = new AppFixtures($this->passwordHasher);

//         // Load the fixtures
//         $fixtures->load($this->entityManager);

//         // Add assertions to verify that fixtures are loaded correctly
//         // For example, you can count the number of entities in the database
//         $userRepository = $this->entityManager->getRepository(User::class);
//         $taskRepository = $this->entityManager->getRepository(Task::class);

//         $this->assertEquals(3, $userRepository->count([])); // Assuming 3 users are loaded by fixtures
//         $this->assertEquals(13, $taskRepository->count([])); // Assuming 13 tasks are loaded by fixtures
//     }
// }