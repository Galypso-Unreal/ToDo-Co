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

        $this->assertEquals(302, $client->getResponse()->getStatusCode()); // check if redirection is good
    }

    public function testListActionAsAdmin()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneBy(['username' => 'Admin']);

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        $client->request('GET', '/users/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testCreateAction(): void
    {
        $client = static::createClient();
        $client->request('GET', '/users/create');

        $this->assertEquals(302, $client->getResponse()->getStatusCode()); // check if redirection is good
    }

    public function testEditAction(): void
    {
        $client = static::createClient();
        $client->request('GET', '/users/1/edit'); // get user id  1

        $this->assertEquals(302, $client->getResponse()->getStatusCode()); // check if redirection is good
    }
}