<?php 


namespace Tests\App\Entity;

use App\Entity\User;
use DateTime;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase{

    function testId(){
       /* auto generate id by database untested */
    }

    function testUsername(){
        $user = new User();
        $user->setUsername('John');
        $this->assertEquals('John',$user->getUsername());
    }

    function testSalt(){
        $user = new User();
        $this->assertEquals(null,$user->getSalt());
    }

    function testPassword(){
        $user = new User();
        $user->setPassword('noencodepass');
        $this->assertEquals('noencodepass',$user->getPassword());
    }

    function testMail(){
        $user = new User();
        $user->setEmail('email@test.com');
        $this->assertEquals('email@test.com',$user->getEmail());
    }

    function testRoles(){
        $user = new User();
        $user->setRoles(['ROLE_TEST']);
        $this->assertEquals(['ROLE_TEST'],$user->getRoles());
    }

    function testEraseCredentials(){
        $user = new User();
        $this->assertEmpty($user->eraseCredentials());
    }   


}