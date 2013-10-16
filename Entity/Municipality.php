<?php

namespace HappyR\LocationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HappyR\LocationBundle\Entity\Municipality
 *
 * @ORM\Table(name="LocationMunicipality")
 * @ORM\Entity(repositoryClass="HappyR\LocationBundle\Entity\ComponentRepository")
 */
class Municipality extends Component
{

    /**
     * @var string $code
     *
     * The Municipality code defined by http://www.skl.se/kommuner_och_landsting/om_kommuner/kommunkoder
     *
     * @ORM\Column(name="code", type="string", length=15, nullable=true)
     */
    private $code;

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

    /**
     * Get the short code for the municipality. The short code is the last 4 digits.
     *
     *
     *
     * @return string
     */
    public function getShortCode()
    {
        return substr($this->code,-4);
    }
}
