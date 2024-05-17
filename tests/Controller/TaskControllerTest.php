<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    // public function testListAction()
    // {

    //     $client = static::createClient([], [
    //         'PHP_AUTH_USER' => 'User',
    //         'PHP_AUTH_PW'   => 'user',
    //     ], [
    //         'ROLE_USER', // Ajoutez ici le ou les rôles que vous souhaitez attribuer à l'utilisateur
    //     ]);

    //     $client->request('GET', '/tasks');

    //     // Vérifier si la réponse est réussie (code 200)
    //     $this->assertSame(200, $client->getResponse()->getStatusCode());
    // }

    public function testCreateAction(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneBy(['username' => 'User']);

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        $client->request('GET', '/tasks/create');

        $this->assertResponseIsSuccessful();
    }

    public function testEditAction(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneBy(['username' => 'User']);

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        $client->request('GET', '/tasks/100/edit');

        $this->assertResponseIsSuccessful();
    }

    public function testToggleTaskAction(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneBy(['username' => 'User']);

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        $client->request('GET', '/tasks/100/toggle');

        $client->followRedirect();

        $this->assertRouteSame('task_list');

        $this->assertResponseIsSuccessful();
    }

    public function testDeleteTaskAction(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneBy(['username' => 'User']);

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        $client->request('GET', '/tasks/100/delete');

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
}