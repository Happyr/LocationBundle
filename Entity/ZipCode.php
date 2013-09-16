<?php

namespace HappyR\LocationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HappyR\LocationBundle\Entity\ZipCode
 *
 * @ORM\Table(name="LocationZipCode")
 * @ORM\Entity(repositoryClass="HappyR\LocationBundle\Entity\ComponentRepository")
 */
class ZipCode extends LocationObject
{
    public function __construct($code)
    {
        parent::__construct($code,$code);
    }

    public function getCode()
    {
        return $this->getName();
    }

    public function setCode($code)
    {
        $this->setName($code);
    }

}
