<?php

namespace Happyr\LocationBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Happyr\LocationBundle\Entity\BaseLocation.
 *
 * @ORM\MappedSuperclass
 */
class BaseLocation
{
    /**
     * @var int id
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
     * @var Country|null
     *
     * @ORM\ManyToOne(targetEntity="Happyr\LocationBundle\Entity\Country", cascade={"persist"})
     * @Assert\Type(type="Happyr\LocationBundle\Entity\Country", message="happyr.location.form.error.country")
     */
    protected $country;

    /**
     * @var City|null city
     *
     * @ORM\ManyToOne(targetEntity="Happyr\LocationBundle\Entity\City", cascade={"persist"})
     * @Assert\Type(type="Happyr\LocationBundle\Entity\City", message="happyr.location.form.error.city")
     */
    protected $city;

    /**
     * @var Municipality|null
     *
     * @ORM\ManyToOne(targetEntity="Happyr\LocationBundle\Entity\Municipality", cascade={"persist"})
     */
    protected $municipality;

    /**
     * @var Region|null region
     *
     * @ORM\ManyToOne(targetEntity="Happyr\LocationBundle\Entity\Region", cascade={"persist"})
     */
    protected $region;

    /**
     * @var string zipCode
     *
     * @ORM\Column(type="string", length=10, nullable=true)
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
     * Try to return the location or the name of the city.
     *
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
     * Removes all data associated with the location.
     */
    public function clear()
    {
        $this->location = '';
        $this->country = null;
        $this->city = null;
        $this->municipality = null;
        $this->region = null;
        $this->zipCode = '';
        $this->address = '';
        $this->lng = 0;
        $this->lat = 0;
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the country code or fallback on SE.
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
     * @return array
     */
    public function getCoordinates()
    {
        return array('lng' => $this->lng, 'lat' => $this->lat);
    }

    /**
     * @return bool
     */
    public function hasCoordinates()
    {
        return $this->lng != 0 && $this->lat != 0;
    }

    /**
     * alias for getCoordinates.
     *
     *
     * @return array
     */
    public function getCoords()
    {
        return $this->getCoordinates();
    }

    /**
     * This is a long string that describes the location.
     *
     * @param string $str
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
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param City|null $city
     *
     * @return $this
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return City|null
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param Country|null $country
     *
     * @return $this
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return Country|null
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param float $lat
     *
     * @return $this
     */
    public function setLat($lat)
    {
        if (empty($lat)) {
            $lat = 0;
        }
        $this->lat = $lat;

        return $this;
    }

    /**
     * @return float
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * @param float $lng
     *
     * @return $this
     */
    public function setLng($lng)
    {
        if (empty($lng)) {
            $lng = 0;
        }
        $this->lng = $lng;

        return $this;
    }

    /**
     * @return float
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param Municipality|null $municipality
     *
     * @return $this
     */
    public function setMunicipality($municipality)
    {
        $this->municipality = $municipality;

        return $this;
    }

    /**
     * @return Municipality|null
     */
    public function getMunicipality()
    {
        return $this->municipality;
    }

    /**
     * @param Region|null $region
     *
     * @return $this
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * @return Region|null
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param string $zipCode
     *
     * @return $this
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }
}
