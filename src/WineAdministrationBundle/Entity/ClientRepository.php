<?php

namespace WineAdministrationBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ClientRepository
 */
class ClientRepository extends EntityRepository
{
    /**
     * Suche nach Vor und Nachnamen
     * 
     * @param array $criteria
     * @return Client
     */
    public function getClientByFullName(array $criteria)
    {
        $qb = $this->createQueryBuilder('c');
        $query = $qb->select('c')
                ->where(
                    $qb->expr()->andX(
                        $qb->expr()->like('c.surname', ':nameOne'),
                        $qb->expr()->like('c.forename', ':nameTwo')
                    )
                )
                ->orWhere(
                    $qb->expr()->andX(
                        $qb->expr()->like('c.surname', ':nameTwo'),
                        $qb->expr()->like('c.forename', ':nameOne')
                    )
                )
                ->setParameter('nameOne', $criteria[0])
                ->setParameter('nameTwo', $criteria[1]);
 
        return $query->getQuery()->getResult();
    }
    
    /**
     * Suche nach Vor oder Nachnamen
     * 
     * @param String $criteria
     * 
     * @return Client
     */
    public function getClientByName($criteria)
    {
        $qb = $this->createQueryBuilder('c');
        $query = $qb->select('c')
                ->where($qb->expr()->like('c.surname', ':nameOne'))
                ->orWhere($qb->expr()->like('c.forename', ':nameOne'))
                ->setParameter('nameOne', $criteria);
 
        return $query->getQuery()->getResult();
    }
}