<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskTest extends KernelTestCase{

    function testConstruct(): void{
        $task = new Task();
        $this->assertEquals(false,$task->isDone());
        $this->assertEquals(new DateTime(), $task->getCreatedAt());
    }

    function testId(): void{
       /* auto generate id by database untested */
    }

    function testCreatedAt(): void{
        $task = new Task();
        $date = new DateTime('2022-07-03 04:53:53');
        $task->setCreatedAt($date);
        $this->assertEquals(new DateTime('2022-07-03 04:53:53'),$task->getCreatedAt());
    }

    function testTitle(): void{
        $task = new Task();
        $task->setTitle('here');
        $this->assertEquals('here',$task->getTitle());
    }

    function testContent(): void{
        $task = new Task();
        $task->setContent('testContent');
        $this->assertEquals('testContent',$task->getContent());
    }

    function testIsDone(): void{
        $task = new Task();
        $task->toggle(true);
        $this->assertEquals(true,$task->isDone());
    }

    function testUser(): void{
        $user = new User();
        $user->setUsername('john');
        $user->setEmail('john@gmail.com');
        $user->setPassword('pass');

        $task = new Task();
        $task->setUser($user);
        $this->assertEquals($user,$task->getUser());
    }

}