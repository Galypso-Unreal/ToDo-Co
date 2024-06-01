<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\Column(type: "datetime")]
    private $createdAt;

    #[ORM\Column(type: "string")]
    #[Assert\NotBlank(message: "Vous devez saisir un titre.")]
    private $title;

    #[ORM\Column(type: "text")]
    #[Assert\NotBlank(message: "Vous devez saisir du contenu.")]
    private $content;

    #[ORM\Column(type: "boolean")]
    private $isDone;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "tasks")]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "id")]
    private $user;

    /**
     * The above PHP function is a constructor that initializes the createdAt property with the current
     * datetime and sets the isDone property to false.
     */
    public function __construct()
    {
        $this->createdAt = new \Datetime();
        $this->isDone = false;
    }

    /**
     * This PHP function returns the value of the "id" property of the object.
     * 
     * @return The function `getId()` is returning the value of the property `id` of the object.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * This PHP function returns the value of the createdAt property.
     * 
     * @return The `createdAt` property of the object is being returned by the `getCreatedAt` method.
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * The function `setCreatedAt` sets the value of the `createdAt` property in a PHP class.
     * 
     * @param createdAt The `setCreatedAt` function is a method that sets the value of the `createdAt`
     * property of an object to the value passed as a parameter. The parameter `$createdAt` is the
     * value that will be assigned to the `createdAt` property of the object.
     */
    public function setCreatedAt($createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * This PHP function getTitle() returns the title property of the object it is called on.
     * 
     * @return The `title` property of the object is being returned.
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * The function `setTitle` in PHP sets the title of an object.
     * 
     * @param title The `setTitle` function is a method that sets the title of an object to the value
     * passed as a parameter. In this case, the parameter is `$title`, which is the new title that will
     * be assigned to the object.
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * This PHP function named `getContent` returns the content stored in the class property
     * ``.
     * 
     * @return The `getContent()` function is returning the `content` property of the current object.
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * The function `setContent` in PHP sets the content of an object.
     * 
     * @param content The `setContent` function is a method that sets the content of an object to the
     * value passed as a parameter. The parameter `$content` is the new content that will be assigned
     * to the object's `content` property.
     */
    public function setContent($content): void
    {
        $this->content = $content;
    }

    /**
     * The function isDone() in PHP returns the value of the isDone property of the object.
     * 
     * @return The method `isDone()` is returning the value of the property `->isDone`.
     */
    public function isDone()
    {
        return $this->isDone;
    }

    /**
     * The function `toggle` in PHP sets the value of the `isDone` property to the provided flag value.
     * 
     * @param flag The `flag` parameter in the `toggle` function is used to determine the value of the
     * `isDone` property. When the `toggle` function is called with a boolean value for the `$flag`
     * parameter, it will set the `isDone` property of the object to that boolean value
     */
    public function toggle($flag): void
    {
        $this->isDone = $flag;
    }

    /**
     * This PHP function named `getUser` returns the value of the `user` property of the current
     * object.
     * 
     * @return The `getUser()` function is returning the `user` property of the current object.
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * The setUser function in PHP sets the user property of an object.
     * 
     * @param user $user The `setUser` function is a method that sets the value of the `user` property in the
     * class to the value passed as an argument. The parameter `$user` is the value that will be
     * assigned to the `user` property.
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }
}
