<?php
    /**
     * Wein zu Kundenbestellung ORM Objekt Repository
     * 
     * Erweiterung des Wein zu Kundenbestellung Objektes um individuelle Funktionen
     *
     * @author C. Depner
     */
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
            ->leftJoin('wtco.clientOrder', 'co', 'co.id = wtco.client')
            ->leftJoin('co.client', 'c', 'c.id = co.client')
            ->leftJoin('wtco.wine', 'w', 'w.id = wtco.wine')
            ->where(
                $qb->expr()->orX(
                    $qb->expr()->orX(
                        $qb->expr()->like('c.forename', ':name'),
                        $qb->expr()->like('c.surname', ':name')
                    ),
                    $qb->expr()->like('w.name', ':name')
                )
            )
            ->andWhere($qb->expr()->eq('w.available', '1'))
            ->setParameter('name', $criteria);

        return $query->getQuery()->getResult();
    }

    /**
     * Suche nach ID
     *
     * @param String $criteria
     *
     * @return Client
     */
    public function getOrderByID($criteria)
    {
        $qb = $this->createQueryBuilder('wtco');
        $query = $qb->select('wtco')
            ->leftJoin('wtco.clientOrder', 'co', 'co.id = wtco.client')
            ->leftJoin('co.client', 'c', 'c.id = co.client')
            ->leftJoin('wtco.wine', 'w', 'w.id = wtco.wine')
            ->where(
                $qb->expr()->eq('wtco', ':id')
            )
            ->andWhere($qb->expr()->eq('w.available', '1'))
            ->setParameter('id', $criteria);

        return $query->getQuery()->getResult();
    }

    /**
     * Gibt alle Bestellungen zurück
     *
     * @return Client
     */
    public function getAll()
    {
        $qb = $this->createQueryBuilder('wtco');
        $query = $qb->select('wtco')
            ->leftJoin('wtco.clientOrder', 'co', 'co.id = wtco.client')
            ->leftJoin('co.client', 'c', 'c.id = co.client')
            ->leftJoin('wtco.wine', 'w', 'w.id = wtco.wine')
            ->where($qb->expr()->eq('w.available', '1'));

        return $query->getQuery()->getResult();
    }

}