<?php

namespace WineAdministrationBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * WinetoclientorderRepository
 */
class WinetoclientorderRepository extends EntityRepository
{

    /**
     * Suche nach Vor oder Nachnamen
     *
     * @param String $criteria
     *
     * @return Client
     */
    public function getOrderByName($criteria)
    {
        $qb = $this->createQueryBuilder('wtco');
        $query = $qb->select('wtco')
            ->leftJoin('wtco.order', 'co', 'co.id = wtco.client')
            ->leftJoin('co.client', 'c', 'c.id = co.client')
            ->leftJoin('wtco.wine', 'w', 'w.id = wtco.wine')
            ->where(
                $qb->expr()->orX(
                    $qb->expr()->like('c.forename', ':name'),
                    $qb->expr()->like('c.surname', ':name')
                )
            )
            ->orWhere($qb->expr()->like('w.name', ':name'))
            ->setParameter('name', $criteria);

        return $query->getQuery()->getResult();
    }

}