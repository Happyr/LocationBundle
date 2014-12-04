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
     * The Municipality code defined by http://www.skl.se/kommuner_och_landsting/om_kommuner/kommunkoder
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

    /**
     * Get the short code for the municipality. The short code is the last 4 digits.
     *
     *
     *
     * @return string
     */
    public function getShortCode()
    {
        return substr($this->code, -4);
    }
}
