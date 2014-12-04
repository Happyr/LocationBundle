<?php

namespace Happyr\LocationBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * @author Tobias Nyholm
 */
class ComponentRepository extends EntityRepository
{
    /**
     * @param string $name
     *
     * @return array
     */
    public function searchByName($name)
    {
        return $this->createQueryBuilder('e')
            ->where('e.name LIKE :name')
            ->orderBy('e.name', 'DESC')
            ->setParameter('name', '%'.$name.'%')
            ->getResult();
    }
}
