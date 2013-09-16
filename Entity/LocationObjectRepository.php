<?php

namespace HappyR\LocationBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Class LocationObjectRepository
 *
 * @author Tobias Nyholm
 *
 *
 */
class LocationObjectRepository extends EntityRepository
{
    /**
     *
     *
     * @param $name
     *
     * @return array
     */
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
