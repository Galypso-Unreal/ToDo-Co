<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Table(name: "user")]
#[ORM\Entity]
#[UniqueEntity("email")]

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\Column(type: "string", length: 25, unique: true)]
    #[Assert\NotBlank(message: "Vous devez saisir un nom d'utilisateur.")]
    private $username;

    #[ORM\Column(type: "string", length: 64)]
    private $password;

    #[ORM\Column(type: "string", length: 60, unique: true)]
    #[Assert\NotBlank(message: "Vous devez saisir une adresse email.")]
    #[Assert\Email(message: "Le format de l'adresse n'est pas correcte.")]
    private $email;

    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: "user")]
    private $tasks;

    #[ORM\Column(type: "json")]
    private $roles = [];

    /**
     * The above PHP function is a constructor that initializes a new ArrayCollection for the tasks
     * property.
     *
     * @return void
     */


    public function __construct()
    {
        $this->tasks = new ArrayCollection();

    }// End __construct().


    /**
     * This PHP function returns the value of the "id" property of the object.
     *
     * @return mixed value of the property `id` of the object
     */


    public function getId()
    {
        return $this->id;

    }// End getId().


    /**
     * This PHP function returns the username as the user identifier.
     *
     * @return mixed The `username` property of the object is being returned as a string.
     */


    public function getUserIdentifier(): string
    {
        return $this->username;

    }// End getUserIdentifier().


    /**
     * This PHP function returns the username associated with the current object.
     *
     * @return mixed The `getUsername()` function is returning the value of the `username` property of the object
     */


    public function getUsername()
    {
        return $this->username;

    }// End getUsername().


    /**
     * The function `setUsername` in PHP sets the username property of an object.
     *
     * @param mixed $username setUsername parameter
     * @return void value username of the object
     */


    public function setUsername($username): void
    {
        $this->username = $username;

    }// End setUsername().


    /**
     * The getSalt function in PHP returns a nullable string value.
     * 
     * @return ?string The `getSalt()` function is returning a `null` value.
     */


    public function getSalt(): ?string
    {
        return null;

    }// End getSalt().


    /**
     * This PHP function returns the password value as a nullable string.
     * 
     * @return ?string The `getPassword()` function is returning a nullable string value, which means
     * it can return either a string or `null`.
     */


    public function getPassword(): ?string
    {
        return $this->password;

    }// End getPassword().


    /**
     * The function `setPassword` in PHP sets the password for an object.
     *
     * @param mixed $password setPassword parameter The `setPassword` function is a method that sets the password for an object. The
     * function takes one parameter, which is the new password that you want to set for the object.
     * @return void
     */


    public function setPassword($password): void
    {
        $this->password = $password;

    }// End setPassword().


    /**
     * This PHP function named getEmail returns the email property of the object it is called on.
     * 
     * @return void getEmail() function is returning the value of the email property of the object.
     */


    public function getEmail()
    {
        return $this->email;

    }// End getEmail().


    /**
     * The setEmail function in PHP sets the email property of an object.
     *
     * @param mixed $email setEmail parameter. The `setEmail` function is a method that sets the email property of an object to
     * the value passed as a parameter. In this case, the parameter is ``, which is the email
     * address that you want to set for the object.
     * @return void
     */


    public function setEmail($email): void
    {
        $this->email = $email;

    }// End setEmail().


    /**
     * This PHP function named getRoles returns an array of roles.
     * 
     * @return array The `getRoles()` function is returning an array of roles stored in the ``
     * property of the object.
     */


    public function getRoles(): array
    {
        $roles = $this->roles;

        return $roles;

    }// End getRoles().


    /**
     * The function `setRoles` in PHP sets the roles of an object based on the provided array.
     * 
     * @param array $roles setRoles parameter. The `setRoles` function is a method that sets the roles of an object. It
     * takes an array of roles as a parameter and assigns it to the `roles` property of the object.
     */


    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
        
    }// End setRoles().


    /**
     * The eraseCredentials function in PHP is used to remove sensitive data from the user's
     * authentication credentials.
     *
     * @return void
     */


    public function eraseCredentials(): void
    {
    }// End eraseCredentials().

    
}
