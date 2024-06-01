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

    // Construct task with basic datetime and done as false default.
    public function __construct()
    {
        $this->createdAt = new \Datetime();
        $this->isDone = false;
    }

    // Get id of task.
    public function getId()
    {
        return $this->id;
    }

    // Get createAt task date.
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    // Set createAt date for a task.
    public function setCreatedAt($createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    // Get the title of the task.
    public function getTitle()
    {
        return $this->title;
    }

    // Set title task.
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    // Get content from a task.
    public function getContent()
    {
        return $this->content;
    }

    // Set content to a task.
    public function setContent($content): void
    {
        $this->content = $content;
    }

    // Get task if is done or not.
    public function isDone()
    {
        return $this->isDone;
    }

    // Set task if she's done or not.
    public function toggle($flag): void
    {
        $this->isDone = $flag;
    }

    // Add get / set function user.
    public function getUser()
    {
        return $this->user;
    }

    // Set user linked on the task.
    public function setUser($user): void
    {
        $this->user = $user;
    }
}
