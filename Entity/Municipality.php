<?php

namespace Happyr\LocationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Happyr\LocationBundle\Entity\Municipality
 *
 * This is the area you dived a region in
 *
 * @ORM\Table(name="LocationMunicipality")
 * @ORM\Entity(repositoryClass="Happyr\LocationBundle\Entity\ComponentRepository")
 */
class Municipality extends Component
{

    /**
     * @var string $code
     *
     * @ORM\Column(name="code", type="string", length=15, nullable=true)
     */
    protected $code;

    /**
     * Set code
     *
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }
}
