<?php

namespace HappyR\LocationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Locale\Locale;

/**
 * HappyR\LocationBundle\Entity\Country
 *
 * @ORM\Table(name="LocationCountry")
 * @ORM\Entity(repositoryClass="HappyR\LocationBundle\Entity\ComponentRepository")
 */
class Country extends Component
{
    /**
     * @param string $name
     */
    public function __construct($name)
    {
        parent::__construct(strtoupper($name),strtolower($name));
    }

    /**
     * Returns the name of the country with the current locale.
     * If you using twig you may also use the country filter
     *
     *
     * @return mixed
     */
    public function getName()
    {
        $countries = Locale::getDisplayCountries(Locale::getDefault());

        return $countries[$this->getCode()];
    }

    /**
     * Set code
     *
     *
     * @param string $code
     */
    public function setCode($code)
    {
        $this->name = $code;
    }

    /**
     * Get code
     *
     *
     * @return string
     */
    public function getCode()
    {
        return $this->name;
    }

}
