<?php

namespace Happyr\LocationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Intl\Intl;
use Symfony\Component\Locale\Locale;

/**
 * Happyr\LocationBundle\Entity\Country.
 *
 * @ORM\Table(name="LocationCountry")
 * @ORM\Entity()
 */
class Country
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * The 2 letter code for a country
     *
     * @var string
     *
     * @ORM\Column(type="string", length=64)
     *
     * @Assert\Length(max=6)
     * @Assert\NotBlank()
     */
    protected $slug;

    /**
     * @param string $code
     */
    public function __construct($code)
    {
        $this->setCode($code);
    }

    /**
     * Returns the name of the country with the current locale.
     *
     * @return mixed
     */
    public function getName($locale = null)
    {
        return Intl::getRegionBundle()->getCountryName($this->slug, $locale);
    }

    /**
     * @param $code
     *
     * @return $this
     */
    public function setCode($code)
    {
        $this->slug = strtolower($code);

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getCode()
    {
        return $this->slug;
    }
}
