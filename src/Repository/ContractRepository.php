<?php

namespace App\Repository;

use App\Entity\Contract;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Contract|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contract|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contract[]    findAll()
 * @method Contract[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContractRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Contract::class);
    }


    public function exportQuery($dateStart, $dateEnd)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.dateStart >= :dateStart')
            ->setParameter('dateStart', $dateStart)
            ->andWhere('c.dateStart <= :dateEnd')
            ->setParameter('dateEnd', $dateEnd)
            ->getQuery()
            ->getResult();

    }

    // find completed contracts with status != 'Terminé'
    public function findCompletedContracts()
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.dateEnd < :today')
            ->setParameter('today', new \DateTime())
            ->andWhere('c.status != :status')
            ->setParameter('status', 'Terminé')
            ->getQuery()
            ->getResult();
    }

    public function findStartTomorrow()
    {
        return $this->createQueryBuilder('c')
            ->where('c.dateStart = :tomorrow')
            ->setParameter('tomorrow', new \DateTime('tomorrow'))
            ->getQuery()
            ->getResult();
    }

    public function findBetween($dateStart, $dateEnd)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.dateStart >= :dateStart')
            ->setParameter('dateStart', $dateStart)
            ->andWhere('c.dateStart <= :dateEnd')
            ->setParameter('dateEnd', $dateEnd)
            ->orderBy('c.dateStart', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
