<?php

namespace App\Repository;

use App\Entity\Interim;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Interim|null find($id, $lockMode = null, $lockVersion = null)
 * @method Interim|null findOneBy(array $criteria, array $orderBy = null)
 * @method Interim[]    findAll()
 * @method Interim[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InterimRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Interim::class);
    }


    public function findByNameOrFirstname($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.name LIKE :name')
            ->setParameter('name', '%' . $value . '%')
            ->orWhere('i.firstname LIKE :name')
            ->setParameter('name', '%' . $value . '%')
            ->getQuery()
            ->getResult();
    }


}
