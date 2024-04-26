<?php

namespace Tests\AppBundle\Form;

use Symfony\Component\Form\Test\TypeTestCase;
use AppBundle\Form\UserType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\CallbackTransformer;

class UserTypeTest extends TypeTestCase
{
    // public function testSubmitValidData()
    // {
    //     $formData = [
    //         'username' => 'test_user',
    //         'password' => 'password',
    //         'email' => 'test@example.com',
    //         'roles' => 'ROLE_USER',
    //     ];

    //     $form = $this->factory->create(UserType::class);
    //     $form->submit($formData);

    //     $this->assertTrue($form->isSynchronized());
    //     $this->assertEquals($formData['username'], $form->get('username')->getData());
    //     $this->assertEquals($formData['email'], $form->get('email')->getData());
    //     $this->assertEquals($formData['roles'], $form->get('roles')->getData());

    //     // Check password field
    //     $this->assertEquals($formData['password'], $form->get('password')->getData());
    // }

    // Add more test cases as needed
}