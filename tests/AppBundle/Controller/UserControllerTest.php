<?php

namespace Tests\App\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testListAction()
    {
        $client = static::createClient();
        $client->request('GET', '/users/');

        $this->assertEquals(302, $client->getResponse()->getStatusCode()); // check if redirection is good
    }

    // public function testListActionAsAdmin()
    // {
    //     // Créer un client en utilisant la méthode statique createClient
    //     $client = static::createClient([], [
    //         'PHP_AUTH_USER' => 'Admin',
    //         'PHP_AUTH_PW' => 'admin',
    //     ]);

    //     $client->request('GET', '/users/');

    //     $this->assertEquals(200, $client->getResponse()->getStatusCode());
    // }

    public function testCreateAction()
    {
        $client = static::createClient();
        $client->request('GET', '/users/create');

        $this->assertEquals(302, $client->getResponse()->getStatusCode()); // check if redirection is good
    }

    public function testEditAction()
    {
        $client = static::createClient();
        $client->request('GET', '/users/1/edit'); // get user id  1

        $this->assertEquals(302, $client->getResponse()->getStatusCode()); // check if redirection is good
    }
}