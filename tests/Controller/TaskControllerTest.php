<?php

namespace App\Tests\Controller;

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

    public function testCreateAction()
    {
        $client = static::createClient();
        $client->request('GET', '/tasks/create');

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    public function testEditAction()
    {
        $client = static::createClient();
        $client->request('GET', '/tasks/1/edit');

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    public function testToggleTaskAction()
    {
        $client = static::createClient();
        $client->request('GET', '/tasks/1/toggle');

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    public function testDeleteTaskAction()
    {
        $client = static::createClient();
        $client->request('GET', '/tasks/1/delete');

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
}