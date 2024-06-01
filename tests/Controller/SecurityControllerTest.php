<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testLoginPage(): void
    {
        $client = static::createClient();

        $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
    }

    public function testLoginPageAsConnected(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);


        // retrieve the test user
        $testUser = $userRepository->findOneBy(['username' => 'User']);

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        $client->request('GET', '/login');

        $client->followRedirect();

        $this->assertRouteSame('homepage');

        $this->assertResponseIsSuccessful();
    }


    public function testLoginFormSubmission(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('_submit')->form();
        $form['_username'] = 'User';
        $form['_password'] = 'user';

        $client->submit($form);

        $this->assertTrue($client->getContainer()->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'));

        $client->followRedirect();

        // Check if on page homepage
        $this->assertRouteSame('homepage');

        $this->assertResponseIsSuccessful();
    }

    public function testLogoutCheck(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);


        // retrieve the test user
        $testUser = $userRepository->findOneBy(['username' => 'User']);

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        // Disconnect
        $client->request('GET', '/logout');

        // Follow redirect
        $client->followRedirect();

        // Check if on page login
        $this->assertRouteSame('login');

        $this->assertResponseIsSuccessful();
    }
}
