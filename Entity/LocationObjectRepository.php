<?php

namespace HappyR\LocationBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * LocationObjectRepository
 *
 *
 */
class LocationObjectRepository extends EntityRepository
{

    public function searchByName($name)
    {
        return $this->getEntityManager()
            ->createQuery('
                SELECT e FROM '.$this->_entityName.' e
                WHERE e.name LIKE :name
                ORDER BY e.name DESC'
            )
             ->setParameter('name', '%'.$name.'%')
             ->getResult();

    }

}
