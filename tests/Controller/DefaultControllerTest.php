<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user.
        $testUser = $userRepository->findOneBy(['username' => 'User']);

        // simulate $testUser being logged in.
        $client->loginUser($testUser);

        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
    }
}
