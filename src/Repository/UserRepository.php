<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{


    /**
     * Construct base managerRegistry for User.
     * 
     * @param ManagerRegistry $registry interface is part of the Doctrine ORM (Object-Relational Mapping) integration.
     * It provides a way to manage and access the different Doctrine entity managers and connections.
     * The ManagerRegistry is often used for dependency injection into services or controllers where database operations are required.
     * 
     * @return void To use in your class, inject the "registry" service and call the parent constructor
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
        
    }// End __construct().
    

    // Ajouter des méthodes personnalisées ici si nécessaire.
}
