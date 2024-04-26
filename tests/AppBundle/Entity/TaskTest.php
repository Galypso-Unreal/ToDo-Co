<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use DateTime;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase{

    function testConstruct(){
        $task = new Task();
        $this->assertEquals(false,$task->isDone());
        $this->assertEquals(new DateTime(), $task->getCreatedAt());
    }

    function testId(){
       /* auto generate id by database untested */
    }

    function testCreatedAt(){
        $task = new Task();
        $date = new DateTime('2022-07-03 04:53:53');
        $task->setCreatedAt($date);
        $this->assertEquals(new DateTime('2022-07-03 04:53:53'),$task->getCreatedAt());
    }

    function testTitle(){
        $task = new Task();
        $task->setTitle('here');
        $this->assertEquals('here',$task->getTitle());
    }

    function testContent(){
        $task = new Task();
        $task->setContent('testContent');
        $this->assertEquals('testContent',$task->getContent());
    }

    function testIsDone(){
        $task = new Task();
        $task->toggle(true);
        $this->assertEquals(true,$task->isDone());
    }

    function testUser(){
        $user = new User();
        $user->setUsername('john');
        $user->setEmail('john@gmail.com');
        $user->setPassword('pass');

        $task = new Task();
        $task->setUser($user);
        $this->assertEquals($user,$task->getUser());
    }

}