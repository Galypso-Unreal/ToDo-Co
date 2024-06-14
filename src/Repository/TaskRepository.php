<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Task>
 *
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{


    /**
     * Construct base managerRegistry for task.
     * 
     * @param ManagerRegistry $registry  interface is part of the Doctrine ORM (Object-Relational Mapping) integration.
     * It provides a way to manage and access the different Doctrine entity managers and connections.
     * The ManagerRegistry is often used for dependency injection into services or controllers where database operations are required.
     * 
     * @return void To use in your class, inject the "registry" service and call the parent constructor
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);

    }// End __construct().


    /**
     * Retrieves tasks assigned to a specific user.
     * 
     * @param int $userId The ID of the user whose tasks are to be retrieved.
     * @return Task[] Returns an array of Task objects assigned to the specified user.
     */


    public function findTasksByUser(int $userId): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.user = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();

    }// End findTasksByUser().


    /**
     * Retrieves ONE task assigned to a specific user.
     * 
     * @param int $userId The ID of the user whose tasks are to be retrieved.
     * @return Task[] Returns an array of Task objects assigned to the specified user.
     */


    public function findOneTaskByUser(int $userId): ?Task
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.user = :userId')
            ->setParameter('userId', $userId)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
            
    }// End findOneTaskByUser().

    
}
