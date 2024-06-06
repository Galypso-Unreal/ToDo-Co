<?php

namespace App\Tests\Form;

use Symfony\Component\Form\Test\TypeTestCase;
use App\Form\TaskType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class TaskTypeTest extends WebTestCase
{

    // Test if submit data work on task form.
    public function testSubmitValidData()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);


        // Retrieve the test user.
        $testUser = $userRepository->findOneBy(['username' => 'User']);

        // Simulate $testUser being logged in.
        $client->loginUser($testUser);

        // Go to the page create user.
        $crawler = $client->request('GET', '/tasks/create');

        // Check if page is correctlyh loaded.
        $this->assertResponseIsSuccessful();

        // Select form.

        $form = $crawler->selectButton('createTask')->form();

        // Add content to form.
        $form['task[title]'] = 'Test Task';
        $form['task[content]'] = 'This is a Test Task';

        $client->submit($form);

        // Follow redirection.
        $client->followRedirect();

        // Check if task has been created.
        $this->assertResponseIsSuccessful();
    }
}
