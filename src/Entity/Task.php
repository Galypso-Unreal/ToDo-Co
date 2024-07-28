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

    /**
     * @var $id within the `Task` class. This property is intended to store the unique identifier of a task object.
     */
    private $id;

    #[ORM\Column(type: "datetime")]

    /**
     * @var $createdAt within the `Task` class. This property is intended to store the datetime when a task object is created.
     */
    private $createdAt;

    #[ORM\Column(type: "string")]
    #[Assert\NotBlank(message: "Vous devez saisir un titre.")]

    /**
     * @var $title within the `Task` class. This property is intended to store the title of a task object.
     */
    private $title;

    #[ORM\Column(type: "text")]
    #[Assert\NotBlank(message: "Vous devez saisir du contenu.")]

    /**
     * @var $content within the `Task` class. This property is intended to store the content of a task object.
     */
    private $content;

    #[ORM\Column(type: "boolean")]

    /**
     * @var $isDone within the `Task` class. This property is intended to store the status of whether a task is done or not.
     */
    private $isDone;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "tasks")]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "id")]
    
    /** 
     * @var `$user` within the `Task` class. This property is intended to store the relationship to a `User` entity in the context of a task.
     */
    private $user;


    /**
     * The above PHP function is a constructor that initializes the createdAt property with the current
     * datetime and sets the isDone property to false.
     *
     * @return void
     */
    public function __construct()
    {
        $this->createdAt = new \Datetime();
        $this->isDone = false;

    }// End __construct().


    /**
     * This PHP function returns the value of the "id" property of the object.
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;

    }// End getId().


    /**
     * This PHP function returns the value of the createdAt property.
     *
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;

    }// End getCreatedAt().


    /**
     * The function `setCreatedAt` sets the value of the `createdAt` property in a PHP class.
     *
     * @param mixed $createdAt The `setCreatedAt` function is a method that sets the value of the `createdAt`
     *                         property of an object to the value passed as a parameter. The parameter `$createdAt` is the
     *                         value that will be assigned to the `createdAt` property of the object.
     * 
     * @return void
     */
    public function setCreatedAt($createdAt): void
    {
        $this->createdAt = $createdAt;

    }// End setCreatedAt().


    /**
     * This PHP function getTitle() returns the title property of the object it is called on.
     *
     * @return mixed `title` property of the object is being returned.
     */
    public function getTitle()
    {
        return $this->title;

    }// End getTitle().


    /**
     * The function `setTitle` in PHP sets the title of an object.
     *
     * @param mixed $title The `setTitle` function is a method that sets the title of an object to the value
     *                     passed as a parameter. In this case, the parameter is `$title`, which is the new title that will
     *                     be assigned to the object.
     * 
     * @return void
     */
    public function setTitle($title): void
    {
        $this->title = $title;

    }// End setTitle().


    /**
     * This PHP function named `getContent` returns the content stored in the class property
     *
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;

    }// End getContent().


    /**
     * The function `setContent` in PHP sets the content of an object.
     *
     * @param mixed $content The `setContent` function is a method that sets the content of an object to the
     *                       value passed as a parameter. The parameter `$content` is the new content that will be assigned
     *                       to the object's `content` property.
     * 
     * @return void
     */
    public function setContent($content): void
    {
        $this->content = $content;

    }// End setContent().


    /**
     * The function isDone() in PHP returns the value of the isDone property of the object.
     *
     * @return void
     */
    public function isDone()
    {
        return $this->isDone;

    }// End isDone().


    /**
     * The function `toggle` in PHP sets the value of the `isDone` property to the provided flag value.
     *
     * @param string $flag The `flag` parameter in the `toggle` function is used to determine the value of the
     *                     `isDone` property. When the `toggle` function is called with a boolean value for the `$flag`
     *                     parameter, it will set the `isDone` property of the object to that boolean value.
     * 
     * @return void
     */
    public function toggle($flag): void
    {
        $this->isDone = $flag;

    }// End toggle().


    /**
     * This PHP function named `getUser` returns the value of the `user` property of the current
     * object.
     *
     * @return mixed The `getUser()` function is returning the `user` property of the current object.
     */
    public function getUser()
    {
        return $this->user;

    }// End getUser().


    /**
     * The setUser function in PHP sets the user property of an object.
     *
     * @param string $user The `setUser` function is a method that sets the value of the `user` property in the
     *                     class to the value passed as an argument. The parameter `$user` is the value that will be
     *                     assigned to the `user` property.
     * 
     * @return void
     */
    public function setUser($user): void
    {
        $this->user = $user;
        
    }// End setUser().

    
}
