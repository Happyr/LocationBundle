<?php

namespace HappyR\LocationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HappyR\LocationBundle\Entity\ZipCode
 *
 * @ORM\Table(name="LocationZipCode")
 * @ORM\Entity(repositoryClass="HappyR\LocationBundle\Entity\ComponentRepository")
 */
class ZipCode extends Component
{
    /**
     * @param $code
     */
    public function __construct($code)
    {
        parent::__construct($code,$code);
    }

    /**
     *
     *
     *
     * @return mixed
     */
    public function getCode()
    {
        return $this->getName();
    }

    /**
     *
     *
     * @param $code
     *
     */
    public function setCode($code)
    {
        $this->setName($code);
    }

}
