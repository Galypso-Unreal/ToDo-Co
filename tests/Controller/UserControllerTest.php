<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{


    /**
     * Test if get users redirect when not connected.
     */
    public function testListAction(): void
    {
        $client = static::createClient();

        $client->request('GET', '/users/');

        $client->followRedirect();

        $this->assertRouteSame('login');

        $this->assertResponseIsSuccessful();

    }// End testListAction().


    /**
     * Test if get users not work if not admin.
     */
    public function testListActionAsUser(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);

        // Retrieve the test user.
        $testUser = $userRepository->findOneBy(['username' => 'user']);

        // Simulate $testUser being logged in.
        $client->loginUser($testUser);

        $client->request('GET', '/users/');

        // Follow redirection.
        $client->followRedirect();

        // Check if redirection is login.
        $this->assertRouteSame('login');

        // Follow redirection.
        $crawler = $client->followRedirect();

        // Check if redirection is login.
        $this->assertRouteSame('homepage');


        // Assert that the response contains the expected message.
        $this->assertGreaterThan(
            0,
            $crawler->filter('div.alert-danger:contains("Vous devez être administrateur pour accéder à cette page.")')->count(),
            'The expected access denied message was not found in a div with class alert-danger.'
        );

    }// End testListActionAsUser().


    /**
     * Test if list of users work when admin connected.
     */
    public function testListActionAsAdmin()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // Retrieve the test user.
        $testUser = $userRepository->findOneBy(['username' => 'admin']);

        // Simulate $testUser being logged in.
        $client->loginUser($testUser);

        $client->request('GET', '/users/');

        $this->assertResponseIsSuccessful();

        $this->assertRouteSame('user_list');

    }// End testListActionAsAdmin().


    /**
     * Test create user.
     */
    public function testCreateAction(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // Retrieve the test user.
        $testUser = $userRepository->findOneBy(['username' => 'Admin']);

        // Simulate $testUser being logged in.
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/users/create');

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('addUser')->form();

        // Add content to form for creating a new user.
        $form['user[username]'] = 'NewUserTestController';
        $form['user[password][first]'] = 'password123';
        $form['user[password][second]'] = 'password123';
        $form['user[email]'] = 'NewUserTestController@example.com';
        $form['user[roles]'] = 'ROLE_USER';

        $client->submit($form);

        // Follow redirection.
        $client->followRedirect();

        // Check if user has been created.
        $this->assertResponseIsSuccessful();

        // Retrieve the newly created user.
        $newUser = $userRepository->findOneBy(['username' => 'NewUserTestController']);

        $this->assertNotNull($newUser, 'New user was not created.');

    }// End testCreateAction().


    /**
     * Test edit user.
     */
    public function testEditAction(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $entityManager = static::getContainer()->get('doctrine')->getManager();


        // Retrieve the test user.
        $user = $userRepository->findOneBy(['username' => 'Admin']);

        // Simulate $testUser being logged in.
        $client->loginUser($user);

        $testUser = $userRepository->findOneBy(['username' => 'NewUserTestController']);

        $identifier = $testUser->getId();

        $crawler = $client->request('GET', '/users/'.$identifier.'/edit');

        $form = $crawler->selectButton('modifyUser')->form();

        $this->assertResponseIsSuccessful();

        $form['user[password][first]'] = 'password123';
        $form['user[password][second]'] = 'password123';
        $form['user[roles]'] = 'ROLE_ADMIN';

        // Submit form.
        $client->submit($form);

        // Follow redirection.
        $client->followRedirect();

        // Check if user has been modified.
        $this->assertResponseIsSuccessful();

        $testUser = $userRepository->findOneBy(['username' => 'NewUserTestController']);

        // Assert that the user has the role ROLE_ADMIN.
        $this->assertContains('ROLE_ADMIN', $testUser->getRoles(), 'The user does not have the ROLE_ADMIN.');

        // Remove entity.
        $entityManager->remove($entityManager->getRepository(User::class)->find($testUser->getId()));
        $entityManager->flush();
        
    }// End testEditAction().

    
}
