<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AccesDeniedHandlerTest extends WebTestCase
{


    /**
     * Test user redirect if not admin.
     */
    public function testPageAdminAsUser(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);


        // Retrieve the test user.
        $testUser = $userRepository->findOneBy(['username' => 'User']);

        // Simulate $testUser being logged in.
        $client->loginUser($testUser);

        $client->request('GET', '/users');

        $flashMessages = $client->getRequest()->getSession()->getFlashBag()->get('error');

        $containsText = false;
        foreach ($flashMessages as $message) {
            if (strpos($message, 'Vous devez être administrateur pour accéder à cette page.') !== false) {
                $containsText = true;
                break;
            }
        }

        $this->assertEquals(true, $containsText);

        $client->followRedirect();

        $this->assertRouteSame('login');

        $client->followRedirect();

        $this->assertRouteSame('homepage');

        $this->assertResponseIsSuccessful();
        
    }// End testPageAdminAsUser().

    
}
