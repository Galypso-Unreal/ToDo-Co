<?php 


namespace App\Tests\Entity;

use App\Entity\User;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase{

    function testId(): void{
       /* auto generate id by database untested */
    }

    function testUsername(): void{
        $user = new User();
        $user->setUsername('John');
        $this->assertEquals('John',$user->getUserIdentifier());
    }

    function testSalt(): void{
        $user = new User();
        $this->assertEquals(null,$user->getSalt());
    }

    function testPassword(): void{
        $user = new User();
        $user->setPassword('noencodepass');
        $this->assertEquals('noencodepass',$user->getPassword());
    }

    function testMail(): void{
        $user = new User();
        $user->setEmail('email@test.com');
        $this->assertEquals('email@test.com',$user->getEmail());
    }

    function testRoles(): void{
        $user = new User();
        $user->setRoles(['ROLE_TEST']);
        $this->assertEquals(['ROLE_TEST'],$user->getRoles());
    }

    function testEraseCredentials(): void{
        $user = new User();
        $this->assertEmpty($user->eraseCredentials());
    }   


}