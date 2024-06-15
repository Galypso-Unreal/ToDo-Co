<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserType extends AbstractType
{


    /**
     * The function builds a form with fields for username, password, email, and roles with a model
     * transformer for roles.
     * 
     * @param FormBuilderInterface $builder parameter in the `buildForm` method is an
     * instance of `FormBuilderInterface` class. It is used to define the structure and behavior of the
     * form. You can add form fields, configure options, and apply transformations using the methods
     * provided by the `FormBuilderInterface` class.
     * 
     * @param array $options parameter in the `buildForm` method is an array that can
     * contain various configuration options for the form. These options can be used to customize the
     * behavior and appearance of the form fields. In the provided code snippet, the ``
     * parameter is not being used directly within the `
     * 
     * @return void The `buildForm` function is returning a form builder object with several form
     * fields added to it. The fields being added are 'username' of type TextType, 'password' of type
     * RepeatedType (PasswordType), 'email' of type EmailType, and 'roles' of type ChoiceType with
     * options for 'User' and 'Admin'. Additionally, a model transformer is being added to
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, ['label' => "Nom d'utilisateur"])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les deux mots de passe doivent correspondre.',
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Tapez le mot de passe à nouveau'],
            ])
            ->add('email', EmailType::class, ['label' => 'Adresse email'])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'User' => 'ROLE_USER',
                    'Admin' => 'ROLE_ADMIN',
                ],
            ]);

        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                    if (count($rolesArray) === 0) {
                        return null;
                    } else {
                        return $rolesArray[0];
                    }
                },
                function ($rolesString) {
                    return [$rolesString];
                }
            ));
            
    }// End buildForm().

    
}
