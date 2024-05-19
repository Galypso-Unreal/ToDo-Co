<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    private ?EntityManagerInterface $entityManager = null;

    public function testListAction(): void
    {
        $client = static::createClient();

        $client->request('GET', '/users/');

        $client->followRedirect();

        $this->assertRouteSame('login');

        $this->assertResponseIsSuccessful();
    }

    // public function testListActionAsUser(): void
    // {
    //     $client = static::createClient();

    //     $client->request('GET', '/users/');

    //     $client->followRedirect();

    //     $this->assertRouteSame('homepage');

    //     $this->assertResponseIsSuccessful();
    // }

    public function testListActionAsAdmin()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneBy(['username' => 'Admin']);

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        $client->request('GET', '/users/');

        $this->assertResponseIsSuccessful();
    }

    public function testCreateAction(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneBy(['username' => 'Admin']);

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        $client->request('GET', '/users/create');

        $this->assertResponseIsSuccessful();
    }

    public function testEditAction(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneBy(['username' => 'Admin']);

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        $id = $testUser->getId();

        $client->request('GET', '/users/'.$id.'/edit'); // get user id  1

        $this->assertResponseIsSuccessful();
    }
}