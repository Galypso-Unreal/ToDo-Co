<?php

namespace App\Tests\Controller;

use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    public function testListAction()
    {

        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneBy(['username' => 'User']);

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        $client->request('GET', '/tasks');

        // Check if 200 return
        $this->assertResponseIsSuccessful();
    }

    public function testListDoneAction()
    {

        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneBy(['username' => 'User']);

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        $client->request('GET', '/tasks/done');

        // Check if 200 return
        $this->assertResponseIsSuccessful();
    }

    public function testCreateAction(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneBy(['username' => 'User']);

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/tasks/create');

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('createTask')->form();

        // Add content to form
        $form['task[title]'] = 'Test Task';
        $form['task[content]'] = 'This is a Test Task';

        $client->submit($form);

        // Follow redirection
        $client->followRedirect();

        // Check if task has been created
        $this->assertResponseIsSuccessful();
    }

    public function testEditAction(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);

        $taskRepository = static::getContainer()->get(TaskRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneBy(['username' => 'User']);

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        $task_id = $taskRepository->findOneTaskByUser($testUser->getId())->getId();

        $crawler = $client->request('GET', '/tasks/'.$task_id.'/edit');

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('modifyTask')->form();

        // Add content to form
        $form['task[title]'] = 'Test Task modified';
        $form['task[content]'] = 'This is a Test Task modified';

        $client->submit($form);

        // Follow redirection
        $client->followRedirect();

        // Check if task has been created
        $this->assertResponseIsSuccessful();
    }

    public function testEditAsWrongUserAction(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);

        $taskRepository = static::getContainer()->get(TaskRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneBy(['username' => 'User']);

        // retrieve the test user admin
        $testUserAdmin = $userRepository->findOneBy(['username' => 'Admin']);

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        $task = $taskRepository->findOneTaskByUser($testUserAdmin->getId());

        $client->request('GET', '/tasks/'.$task->getId().'/edit');

        $flashMessages = $client->getRequest()->getSession()->getFlashBag()->get('error');

        $containsText = false;
        foreach ($flashMessages as $message) {
            if (strpos($message, "ne peux pas être modifier par un autre utilisateur") !== false) {
                $containsText = true;
                break;
            }
        }

        $this->assertEquals(true,$containsText);

        $client->followRedirect();

        $this->assertRouteSame('task_list');

        $this->assertResponseIsSuccessful();

    }

    public function testToggleTaskAction(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);

        $taskRepository = static::getContainer()->get(TaskRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneBy(['username' => 'User']);

        $task_id = $taskRepository->findOneTaskByUser($testUser->getId())->getId();

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        $client->request('GET', '/tasks/'.$task_id.'/toggle');

        $client->followRedirect();

        $this->assertRouteSame('task_list');

        $this->assertResponseIsSuccessful();
    }

    public function testToggleTaskActionAsNotDone(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);

        $taskRepository = static::getContainer()->get(TaskRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneBy(['username' => 'User']);

        $task_id = $taskRepository->findOneBy(['user' => $testUser->getId()])->getId();

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        $client->request('GET', '/tasks/'.$task_id.'/toggle');

        $flashMessages = $client->getRequest()->getSession()->getFlashBag()->get('success');

        $containsText = false;
        foreach ($flashMessages as $message) {
            if (strpos($message, "a bien été marquée comme non faite.") !== false) {
                $containsText = true;
                break;
            }
        }

        $this->assertEquals(true,$containsText);

        $client->followRedirect();

        $this->assertRouteSame('task_list');

        $this->assertResponseIsSuccessful();
    }

    public function testDeleteTaskAction(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);

        $taskRepository = static::getContainer()->get(TaskRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneBy(['username' => 'User']);

        $task_id = $taskRepository->findOneTaskByUser($testUser->getId())->getId();

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        $client->request('GET', '/tasks/'.$task_id.'/delete');

        $this->assertTrue($client->getRequest()->getSession()->getFlashBag()->has('success'));

        $client->followRedirect();

        $this->assertRouteSame('task_list');

        $this->assertResponseIsSuccessful();
    }

    public function testDeleteTaskActionWrongUser(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);

        $taskRepository = static::getContainer()->get(TaskRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneBy(['username' => 'User']);

        $testUserWrong = $userRepository->findOneBy(['username' => 'Admin']);

        $task_id = $taskRepository->findOneTaskByUser($testUser->getId())->getId();

        // simulate $testUser being logged in
        $client->loginUser($testUserWrong);

        $client->request('GET', '/tasks/'.$task_id.'/delete');

        $this->assertTrue($client->getRequest()->getSession()->getFlashBag()->has('error'));

        $client->followRedirect();

        $this->assertResponseIsSuccessful();
    }
}