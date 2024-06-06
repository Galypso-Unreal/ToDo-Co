<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TaskType extends AbstractType
{

    /**
     * The function builds a form with fields for title and content using Symfony's
     * FormBuilderInterface.
     * 
     * @param FormBuilderInterface builder The `$builder` parameter in the `buildForm` method is an
     * instance of `FormBuilderInterface` class. It is used to define the structure and behavior of the
     * form being built. You can use methods provided by the `FormBuilderInterface` class to add form
     * fields, configure options, and handle form
     * @param array options The `$options` parameter in the `buildForm` method is an array that can be
     * used to pass additional options to the form builder. These options can be used to customize the
     * behavior or appearance of the form fields. For example, you can pass validation constraints,
     * default values, or other configuration settings
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('content', TextareaType::class)
        ;
    }
    //end buildForm()
}
