<?php

namespace HappyR\LocationBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * HappyR\LocationBundle\Entity\Location
 *
 * @ORM\Table(name="Location")
 * @ORM\Entity()
 */
class Location
{
    /**
     * @var integer id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string location
     *
     * The complete address. This field is used with google autocomplete
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $location = '';

    /**
     * @var \HappyR\LocationBundle\Entity\Country country
     *
     * @ORM\ManyToOne(targetEntity="Country", cascade={"persist"})
     * @Assert\Type(type="HappyR\LocationBundle\Entity\Country", message="location.form.error.country")
     */
    protected $country;

    /**
     * @var \HappyR\LocationBundle\Entity\City city
     *
     * @ORM\ManyToOne(targetEntity="City", cascade={"persist"})
     * @Assert\Type(type="HappyR\LocationBundle\Entity\City", message="location.form.error.city")
     */
    protected $city;

    /**
     * @var \HappyR\LocationBundle\Entity\Municipality municipality
     *
     * @ORM\ManyToOne(targetEntity="Municipality", cascade={"persist"})
     */
    protected $municipality;

    /**
     * @var \HappyR\LocationBundle\Entity\Region region
     *
     * @ORM\ManyToOne(targetEntity="Region", cascade={"persist"})
     */
    protected $region;

    /**
     * @var \HappyR\LocationBundle\Entity\State state
     *
     * @ORM\ManyToOne(targetEntity="State", cascade={"persist"})
     */
    protected $state;

    /**
     * @var \HappyR\LocationBundle\Entity\ZipCode zipCode
     *
     * @ORM\ManyToOne(targetEntity="ZipCode", cascade={"persist"})
     */
    protected $zipCode;

    /**
     *  @var string address
     *
     * The address
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address = '';

    /**
     * @var float lng
     *
     * @ORM\Column(type="decimal", scale=6)
     */
    private $lng = 0;

    /**
     * @var float lat
     *
     * @ORM\Column(type="decimal", scale=6)
     */
    private $lat = 0;

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
     * @param \HappyR\LocationBundle\Entity\City $city
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
     * @return \HappyR\LocationBundle\Entity\City
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     *
     * @param \HappyR\LocationBundle\Entity\Country $country
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
     * @return \HappyR\LocationBundle\Entity\Country
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
     * @param \HappyR\LocationBundle\Entity\Municipality $municipality
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
     * @return \HappyR\LocationBundle\Entity\Municipality
     */
    public function getMunicipality()
    {
        return $this->municipality;
    }

    /**
     *
     * @param \HappyR\LocationBundle\Entity\Region $region
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
     * @return \HappyR\LocationBundle\Entity\Region
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     *
     * @param \HappyR\LocationBundle\Entity\State $state
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
     * @return \HappyR\LocationBundle\Entity\State
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     *
     * @param \HappyR\LocationBundle\Entity\ZipCode $zipCode
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
     * @return \HappyR\LocationBundle\Entity\ZipCode
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }
}