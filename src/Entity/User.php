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

    // Contruct array collection for user (a user can have multiples tasks).
    public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }

    // Get id of user.
    public function getId()
    {
        return $this->id;
    }

    // Get user identifier (here username).
    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    // Get username of user.
    public function getUsername()
    {
        return $this->username;
    }

    // Set username of user.
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    // Get salt security of user.
    public function getSalt(): ?string
    {
        return null;
    }

    // Get password of user.
    public function getPassword(): ?string
    {
        return $this->password;
    }

    // Set password user.
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    // Get user email.
    public function getEmail()
    {
        return $this->email;
    }

    // Set user email.
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    // Get roles of user (admin or user).
    public function getRoles(): array
    {
        $roles = $this->roles;

        return $roles;
    }

    // Set user roles.
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    // Delete credentails user.
    public function eraseCredentials(): void
    {
    }
}
