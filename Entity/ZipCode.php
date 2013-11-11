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
     * @param string $code
     *
     * @return $this
     */
    public function setCode($code)
    {
        $this->setName($code);

        return $this;
    }
}
