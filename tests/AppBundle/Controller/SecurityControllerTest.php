<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testLoginPage()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    public function testLoginFormSubmission()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('_submit')->form();
        $form['_username'] = 'User';
        $form['_password'] = 'user';

        $client->submit($form);

        $this->assertTrue($client->getContainer()->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'));
    }

    public function testLogout()
    {
        $client = static::createClient();

        $client->request('GET', '/logout');

        $this->assertTrue($client->getResponse()->isRedirect());
    }
    
}