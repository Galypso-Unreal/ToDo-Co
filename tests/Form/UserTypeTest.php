<?php

namespace App\Tests\Form;

use App\Entity\User;
use Symfony\Component\Form\Test\TypeTestCase;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\CallbackTransformer;

class UserTypeTest extends WebTestCase
{

    private ?EntityManagerInterface $entityManager = null;

    // Test if submit data work on user form.
    public function testSubmitValidData(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);

        // Retrieve the test user.
        $testUser = $userRepository->findOneBy(['username' => 'Admin']);

        // Simulate $testUser being logged in.
        $client->loginUser($testUser);

        // Go to the page create user.
        $crawler = $client->request('GET', '/users/create');

        // Check if page is correctlyh loaded.
        $this->assertResponseIsSuccessful();

        // Query for a user named "test-user-phpunit".
        $testUser = $userRepository->findOneBy(['username' => 'testuser-phpunit']);

        // If test-user-phpunit exists, delete him.
        if ($testUser) {
            $this->entityManager->remove($testUser);
            $this->entityManager->flush();
        }

        // Select form.

        $form = $crawler->selectButton('addUser')->form();

        // Add content to form.
        $form['user[username]'] = 'testuser-phpunit';
        $form['user[email]'] = 'testuser-phpunit@example.com';
        $form['user[password][first]'] = 'password123';
        $form['user[password][second]'] = 'password123';
        $form['user[roles]'] = 'ROLE_USER';

        // Submit form.
        $client->submit($form);

        // Follow redirection.
        $client->followRedirect();

        // Check if user has been created.
        $this->assertResponseIsSuccessful();
        
    }// End testSubmitValidData().
}
