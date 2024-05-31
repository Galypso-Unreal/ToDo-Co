<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AccesDeniedHandlerTest extends WebTestCase

{
    public function testPageAdminAsUser(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        

        // retrieve the test user
        $testUser = $userRepository->findOneBy(['username' => 'User']);

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/users');

        $flashMessages = $client->getRequest()->getSession()->getFlashBag()->get('error');

        $containsText = false;
        foreach ($flashMessages as $message) {
            if (strpos($message, 'Vous devez être administrateur pour accéder à cette page.') !== false) {
                $containsText = true;
                break;
            }
        }

        $this->assertEquals(true,$containsText);

        $client->followRedirect();

        $this->assertRouteSame('login');

        $client->followRedirect();

        $this->assertRouteSame('homepage');

        $this->assertResponseIsSuccessful();
    }
}