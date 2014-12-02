<?php

namespace Happyr\LocationBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Happyr\LocationBundle\Entity\BaseLocation
 *
 * @ORM\MappedSuperclass
 * 
 */
class BaseLocation
{
    /**
     * @var integer id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string location
     *
     * The complete address. This field is used with google autocomplete
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $location = '';

    /**
     * @var \Happyr\LocationBundle\Entity\Country country
     *
     * @ORM\ManyToOne(targetEntity="Happyr\LocationBundle\Entity\Country", cascade={"persist"})
     * @Assert\Type(type="Happyr\LocationBundle\Entity\Country", message="location.form.error.country")
     */
    protected $country;

    /**
     * @var \Happyr\LocationBundle\Entity\City city
     *
     * @ORM\ManyToOne(targetEntity="Happyr\LocationBundle\Entity\City", cascade={"persist"})
     * @Assert\Type(type="Happyr\LocationBundle\Entity\City", message="location.form.error.city")
     */
    protected $city;

    /**
     * @var \Happyr\LocationBundle\Entity\Municipality municipality
     *
     * @ORM\ManyToOne(targetEntity="Happyr\LocationBundle\Entity\Municipality", cascade={"persist"})
     */
    protected $municipality;

    /**
     * @var \Happyr\LocationBundle\Entity\Region region
     *
     * @ORM\ManyToOne(targetEntity="Happyr\LocationBundle\Entity\Region", cascade={"persist"})
     */
    protected $region;

    /**
     * @var \Happyr\LocationBundle\Entity\State state
     *
     * @ORM\ManyToOne(targetEntity="Happyr\LocationBundle\Entity\State", cascade={"persist"})
     */
    protected $state;

    /**
     * @var \Happyr\LocationBundle\Entity\ZipCode zipCode
     *
     * @ORM\ManyToOne(targetEntity="Happyr\LocationBundle\Entity\ZipCode", cascade={"persist"})
     */
    protected $zipCode;

    /**
     *  @var string address
     *
     * The address
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $address = '';

    /**
     * @var float lng
     *
     * @ORM\Column(type="decimal", scale=6)
     */
    protected $lng = 0;

    /**
     * @var float lat
     *
     * @ORM\Column(type="decimal", scale=6)
     */
    protected $lat = 0;

    /**
     * Try to return the location or the name of the city
     * @return string
     */
    public function __toString()
    {
        if (empty($this->location) && $this->city) {
            return $this->city->__toString();
        }

        return $this->location;
    }

    /**
     * Removes all data associated with the location
     */
    public function clear()
    {
        $this->location = '';
        $this->country = null;
        $this->city = null;
        $this->municipality = null;
        $this->region = null;
        $this->zipCode = null;
        $this->address = '';
        $this->lng = 0;
        $this->lat = 0;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the country code or fallback on SE
     *
     * @return string
     */
    public function getCountryCode()
    {
        if ($this->getCountry() == null) {
            //fallback
            return 'SE';
        }

        return $this->getCountry()->getCode();
    }

    /**
     *
     *
     * @return array
     */
    public function getCoordinates()
    {
        return array('lng' => $this->lng, 'lat' => $this->lat);
    }

    /**
     *
     *
     * @return bool
     */
    public function hasCoordinates()
    {
        return $this->lng != 0 && $this->lat != 0;
    }

    /**
     * alias for getCoordinates
     *
     *
     * @return array
     */
    public function getCoords()
    {
        return $this->getCoordinates();
    }

    /**
     * This is a long string that describes the location
     *
     * @param string $str
     *
     */
    public function setLocation($str)
    {
        if ($str != null) {
            $this->location = $str;
        } else {
            $this->location = '';
        }
    }

    /**
     *
     * @param string $address
     *
     * @return $this
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     *
     * @param \Happyr\LocationBundle\Entity\City $city
     *
     * @return $this
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     *
     * @return \Happyr\LocationBundle\Entity\City
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     *
     * @param \Happyr\LocationBundle\Entity\Country $country
     *
     * @return $this
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     *
     * @return \Happyr\LocationBundle\Entity\Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     *
     * @param float $lat
     *
     * @return $this
     */
    public function setLat($lat)
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     *
     * @return float
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     *
     * @param float $lng
     *
     * @return $this
     */
    public function setLng($lng)
    {
        $this->lng = $lng;

        return $this;
    }

    /**
     *
     * @return float
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     *
     * @param \Happyr\LocationBundle\Entity\Municipality $municipality
     *
     * @return $this
     */
    public function setMunicipality($municipality)
    {
        $this->municipality = $municipality;

        return $this;
    }

    /**
     *
     * @return \Happyr\LocationBundle\Entity\Municipality
     */
    public function getMunicipality()
    {
        return $this->municipality;
    }

    /**
     *
     * @param \Happyr\LocationBundle\Entity\Region $region
     *
     * @return $this
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     *
     * @return \Happyr\LocationBundle\Entity\Region
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     *
     * @param \Happyr\LocationBundle\Entity\State $state
     *
     * @return $this
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     *
     * @return \Happyr\LocationBundle\Entity\State
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     *
     * @param \Happyr\LocationBundle\Entity\ZipCode $zipCode
     *
     * @return $this
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    /**
     *
     * @return \Happyr\LocationBundle\Entity\ZipCode
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }
}