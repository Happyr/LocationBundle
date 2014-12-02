<?php

namespace Happyr\LocationBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Class LocationObjectRepository
 *
 * @author Tobias Nyholm
 *
 *
 */
class ComponentRepository extends EntityRepository
{
    /**
     *
     *
     * @param string $name
     *
     * @return array
     */
    public function searchByName($name)
    {
        return $this->getEntityManager()
            ->createQuery(
                '
                                SELECT e FROM ' . $this->_entityName . ' e
                WHERE e.name LIKE :name
                ORDER BY e.name DESC'
            )
            ->setParameter('name', '%' . $name . '%')
            ->getResult();
    }
}
