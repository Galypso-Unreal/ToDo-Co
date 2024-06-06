<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{

    // Test index 200 response.
    public function testIndex(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // Retrieve the test user.
        $testUser = $userRepository->findOneBy(['username' => 'User']);

        // Simulate $testUser being logged in.
        $client->loginUser($testUser);

        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();

    }// End testIndex().


}
