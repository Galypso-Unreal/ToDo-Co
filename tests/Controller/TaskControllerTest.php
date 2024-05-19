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

    public function testCreateAction(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneBy(['username' => 'User']);

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        $client->request('GET', '/tasks/create');

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

        $client->request('GET', '/tasks/'.$task_id.'/edit');

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