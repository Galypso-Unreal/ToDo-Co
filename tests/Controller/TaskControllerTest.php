<?php

namespace App\Tests\Controller;

use App\Controller\TaskController;
use App\Entity\Task;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Response;
use Symfony\Contracts\Cache\CacheInterface;
use Twig\Environment;

class TaskControllerTest extends WebTestCase
{

    // Test if 200 response with tasks list.
    public function testListAction()
    {

        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // Retrieve the test user.
        $testUser = $userRepository->findOneBy(['username' => 'User']);

        // Simulate $testUser being logged in.
        $client->loginUser($testUser);

        $client->request('GET', '/tasks');

        // Check if 200 return.
        $this->assertResponseIsSuccessful();
    }
    //end testListAction

    // Test list of tasks done.
    public function testlistActionDone()
    {

        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // Retrieve the test user.
        $testUser = $userRepository->findOneBy(['username' => 'User']);

        // Simulate $testUser being logged in.
        $client->loginUser($testUser);

        $client->request('GET', '/tasks/done');

        // Check if 200 return.
        $this->assertResponseIsSuccessful();
    }
    //end testlistActionDone

    // Test if cache list done work.
    public function testListDoneActionCache()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $cache = static::getContainer()->get(CacheInterface::class);

        // Retrieve the test user.
        $testUser = $userRepository->findOneBy(['username' => 'User']);

        // Simulate $testUser being logged in.
        $client->loginUser($testUser);

        $client->request('GET', '/tasks/done');

        // Check if 200 return.
        $this->assertResponseIsSuccessful();

        // Check if the data is stored in cache.
        $cachedData = $cache->get('tasks_list_done', function () {
            return null;
        });

        $this->assertNotNull($cachedData, 'Cached data should not be null');
        $this->assertIsArray($cachedData, 'Cached data should be an array');
    }
    //end testListDoneActionCache

    // Test if create task work and redirect with success response.
    public function testCreateAction(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // Retrieve the test user.
        $testUser = $userRepository->findOneBy(['username' => 'User']);

        // Simulate $testUser being logged in.
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/tasks/create');

        $this->assertResponseIsSuccessful();

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
    //end testCreateAction

    // Test if can edit task.
    public function testEditAction(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);

        $taskRepository = static::getContainer()->get(TaskRepository::class);

        // Retrieve the test user.
        $testUser = $userRepository->findOneBy(['username' => 'User']);

        // Simulate $testUser being logged in.
        $client->loginUser($testUser);

        $task_id = $taskRepository->findOneTaskByUser($testUser->getId())->getId();

        $crawler = $client->request('GET', '/tasks/' . $task_id . '/edit');

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('modifyTask')->form();

        // Add content to form.
        $form['task[title]'] = 'Test Task modified';
        $form['task[content]'] = 'This is a Test Task modified';

        $client->submit($form);

        // Follow redirection.
        $client->followRedirect();

        // Check if task has been created.
        $this->assertResponseIsSuccessful();
    }
    //end testEditAction

    // Test if edit with wrong user is not working (not admin).
    public function testEditAsWrongUserAction(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);

        $taskRepository = static::getContainer()->get(TaskRepository::class);

        // Retrieve the test user.
        $testUser = $userRepository->findOneBy(['username' => 'User']);

        // Retrieve the test user admin.
        $testUserAdmin = $userRepository->findOneBy(['username' => 'Admin']);

        // Simulate $testUser being logged in.
        $client->loginUser($testUser);

        $task = $taskRepository->findOneTaskByUser($testUserAdmin->getId());

        $client->request('GET', '/tasks/' . $task->getId() . '/edit');

        $flashMessages = $client->getRequest()->getSession()->getFlashBag()->get('error');

        $containsText = false;
        foreach ($flashMessages as $message) {
            if (strpos($message, "ne peux pas être modifier par un autre utilisateur") !== false) {
                $containsText = true;
                break;
            }
        }

        $this->assertEquals(true, $containsText);

        $client->followRedirect();

        $this->assertRouteSame('task_list');

        $this->assertResponseIsSuccessful();
    }
    //end testEditAsWrongUserAction

    // Test if change status of task work.
    public function testToggleTaskAction(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);

        $taskRepository = static::getContainer()->get(TaskRepository::class);

        // Retrieve the test user.
        $testUser = $userRepository->findOneBy(['username' => 'User']);

        $task_id = $taskRepository->findOneTaskByUser($testUser->getId())->getId();

        // Simulate $testUser being logged in.
        $client->loginUser($testUser);

        $client->request('GET', '/tasks/' . $task_id . '/toggle');

        $client->followRedirect();

        $this->assertRouteSame('task_list');

        $this->assertResponseIsSuccessful();
    }
    //end testToggleTaskAction

    // Test status has not done work.
    public function testToggleTaskActionAsNotDone(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);

        $taskRepository = static::getContainer()->get(TaskRepository::class);

        // Retrieve the test user.
        $testUser = $userRepository->findOneBy(['username' => 'User']);

        $task_id = $taskRepository->findOneBy(['user' => $testUser->getId()])->getId();

        // Simulate $testUser being logged in.
        $client->loginUser($testUser);

        $client->request('GET', '/tasks/' . $task_id . '/toggle');

        $flashMessages = $client->getRequest()->getSession()->getFlashBag()->get('success');

        $containsText = false;
        foreach ($flashMessages as $message) {
            if (strpos($message, "a bien été marquée comme non faite.") !== false) {
                $containsText = true;
                break;
            }
        }

        $this->assertEquals(true, $containsText);

        $client->followRedirect();

        $this->assertRouteSame('task_list');

        $this->assertResponseIsSuccessful();
    }
    //end testToggleTaskActionAsNotDone

    // Test if can delete task.
    public function testDeleteTaskAction(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);

        $taskRepository = static::getContainer()->get(TaskRepository::class);

        // Retrieve the test user.
        $testUser = $userRepository->findOneBy(['username' => 'User']);

        $task_id = $taskRepository->findOneTaskByUser($testUser->getId())->getId();

        // Simulate $testUser being logged in.
        $client->loginUser($testUser);

        $client->request('GET', '/tasks/' . $task_id . '/delete');

        $this->assertTrue($client->getRequest()->getSession()->getFlashBag()->has('success'));

        $client->followRedirect();

        $this->assertRouteSame('task_list');

        $this->assertResponseIsSuccessful();
    }
    //end testDeleteTaskAction

    // Test if delete task with wrong user not working.
    public function testDeleteTaskActionWrongUser(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);

        $taskRepository = static::getContainer()->get(TaskRepository::class);

        // Retrieve the test user.
        $testUser = $userRepository->findOneBy(['username' => 'User']);

        $testUserWrong = $userRepository->findOneBy(['username' => 'Admin']);

        $task_id = $taskRepository->findOneTaskByUser($testUser->getId())->getId();

        // Simulate $testUser being logged in.
        $client->loginUser($testUserWrong);

        $client->request('GET', '/tasks/' . $task_id . '/delete');

        $this->assertTrue($client->getRequest()->getSession()->getFlashBag()->has('error'));

        $client->followRedirect();

        $this->assertResponseIsSuccessful();
    }
    //end testDeleteTaskActionWrongUser
}
