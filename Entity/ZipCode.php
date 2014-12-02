<?php

namespace Happyr\LocationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Happyr\LocationBundle\Entity\ZipCode
 *
 * @ORM\Table(name="LocationZipCode")
 * @ORM\Entity(repositoryClass="Happyr\LocationBundle\Entity\ComponentRepository")
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
