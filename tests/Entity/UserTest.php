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

    /**
     * Set up doctrine for entity manager.
     */
    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

    }// End setUp().


    /**
     * Test id function.
     */
    public function testId(): void
    {


        $userRepository = static::getContainer()->get(UserRepository::class);

        // Query for a user named "test-user-phpunit".
        $testUser = $userRepository->findOneBy(['username' => 'test-user-phpunit']);

        // If test-user-phpunit exists, delete him.
        if (empty($testUser) === false) {
            $this->entityManager->remove($testUser);
            $this->entityManager->flush();
        }


        $user = new User();
        $user->setPassword('noencodepass');
        $user->setEmail('test-user@gmail.com');
        $user->setUsername('test-user-phpunit');


        $this->entityManager->persist($user);
        $this->entityManager->flush();

        // Get task from database.
        $taskRepository = $this->entityManager->getRepository(User::class);
        $savedTask = $taskRepository->find($user->getId());

        // Check if id is correct.
        $this->assertNotNull($savedTask);
        $this->assertIsInt($savedTask->getId());

    }// End testId().


    /**
     * Test get/set username.
     */
    public function testUsername(): void
    {
        $user = new User();
        $user->setUsername('John');
        $this->assertEquals('John', $user->getUserIdentifier());

    }// End testUsername().


    /**
     * Test salt function (working security).
     */
    public function testSalt(): void
    {
        $user = new User();
        $this->assertEquals(null, $user->getSalt());

    }// End testSalt().


    /**
     * Test password managment (not encoded).
     */
    public function testPassword(): void
    {
        $user = new User();
        $user->setPassword('noencodepass');
        $this->assertEquals('noencodepass', $user->getPassword());

    }// End testPassword().


    /**
     * Test mail get/set.
     */
    public function testMail(): void
    {
        $user = new User();
        $user->setEmail('email@test.com');
        $this->assertEquals('email@test.com', $user->getEmail());

    }// End testMail().


    /**
     * Test roles of user.
     */
    public function testRoles(): void
    {
        $user = new User();
        $user->setRoles(['ROLE_TEST']);
        $this->assertEquals(['ROLE_TEST'], $user->getRoles());

    }// End testRoles().


    /**
     * Test erase credential (working security).
     */
    public function testEraseCredentials(): void
    {
        $user = new User();
        $this->assertEmpty($user->eraseCredentials());
        
    }// End testEraseCredentials().

    
}
