<?php

namespace WineAdministrationBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * WineRepository
 */
class WineRepository extends EntityRepository
{

    /**
     * Suche nach Weinnamen
     *
     * @param String $criteria
     *
     * @return Wine
     */
    public function getWineByName($criteria)
    {
        $qb = $this->createQueryBuilder('w');
        $query = $qb->select('w')
            ->where($qb->expr()->like('w.name', ':name'))
            ->setParameter('name', $criteria);

        return $query->getQuery()->getResult();
    }

}