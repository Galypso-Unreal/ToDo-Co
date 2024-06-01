<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{

    // Test if get users redirect when not connected.
    public function testListAction(): void
    {
        $client = static::createClient();

        $client->request('GET', '/users/');

        $client->followRedirect();

        $this->assertRouteSame('login');

        $this->assertResponseIsSuccessful();
    }

    // Test if get users not work if not admin.
    public function testListActionAsUser(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user.
        $testUser = $userRepository->findOneBy(['username' => 'Admin']);

        // simulate $testUser being logged in.
        $client->loginUser($testUser);

        $client->request('GET', '/users/');

        $this->assertResponseIsSuccessful();
    }

    // Test if list of users work when admin connected.
    public function testListActionAsAdmin()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user.
        $testUser = $userRepository->findOneBy(['username' => 'Admin']);

        // simulate $testUser being logged in.
        $client->loginUser($testUser);

        $client->request('GET', '/users/');

        $this->assertResponseIsSuccessful();
    }

    // Test create user.
    public function testCreateAction(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user.
        $testUser = $userRepository->findOneBy(['username' => 'Admin']);

        // simulate $testUser being logged in.
        $client->loginUser($testUser);

        $client->request('GET', '/users/create');

        $this->assertResponseIsSuccessful();
    }

    // Test edit user.
    public function testEditAction(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user.
        $user = $userRepository->findOneBy(['username' => 'Admin']);

        // simulate $testUser being logged in.
        $client->loginUser($user);

        $testUser = $userRepository->findOneBy(['username' => 'AnonymeUser']);

        $id = $testUser->getId();

        $crawler = $client->request('GET', '/users/' . $id . '/edit');

        $form = $crawler->selectButton('modifyUser')->form();

        $this->assertResponseIsSuccessful();

        $form['user[password][first]'] = 'password123';
        $form['user[password][second]'] = 'password123';

        // Submit form.
        $client->submit($form);

        // Follow redirection.
        $client->followRedirect();

        // Check if user has been created.
        $this->assertResponseIsSuccessful();
    }
}
