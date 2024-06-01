<?php


namespace App\Tests\Entity;

use App\Entity\User;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{

    private ?EntityManagerInterface $entityManager = null;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    function testId(): void
    {


        $userRepository = static::getContainer()->get(UserRepository::class);

        // Query for a user named "test-user-phpunit"
        $testUser = $userRepository->findOneBy(['username' => 'test-user-phpunit']);

        // If test-user-phpunit exists, delete him
        if ($testUser) {
            $this->entityManager->remove($testUser);
            $this->entityManager->flush();
        }


        $user = new User();
        $user->setPassword('noencodepass');
        $user->setEmail('test-user@gmail.com');
        $user->setUsername('test-user-phpunit');


        $this->entityManager->persist($user);
        $this->entityManager->flush();

        // Get task from database
        $taskRepository = $this->entityManager->getRepository(User::class);
        $savedTask = $taskRepository->find($user->getId());

        // Check if id is correct
        $this->assertNotNull($savedTask);
        $this->assertIsInt($savedTask->getId());
    }

    function testUsername(): void
    {
        $user = new User();
        $user->setUsername('John');
        $this->assertEquals('John', $user->getUserIdentifier());
    }

    function testSalt(): void
    {
        $user = new User();
        $this->assertEquals(null, $user->getSalt());
    }

    function testPassword(): void
    {
        $user = new User();
        $user->setPassword('noencodepass');
        $this->assertEquals('noencodepass', $user->getPassword());
    }

    function testMail(): void
    {
        $user = new User();
        $user->setEmail('email@test.com');
        $this->assertEquals('email@test.com', $user->getEmail());
    }

    function testRoles(): void
    {
        $user = new User();
        $user->setRoles(['ROLE_TEST']);
        $this->assertEquals(['ROLE_TEST'], $user->getRoles());
    }

    function testEraseCredentials(): void
    {
        $user = new User();
        $this->assertEmpty($user->eraseCredentials());
    }
}
