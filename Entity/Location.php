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
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * The complete address. This field is used with google autocomplete
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $location='';

    /**
     *
     * @ORM\ManyToOne(targetEntity="Country", cascade={"persist"})
     * @Assert\Type(type="HappyR\LocationBundle\Entity\Country", message="location.form.error.country")
     */
    protected $country;

    /**
     *
     * @ORM\ManyToOne(targetEntity="City", cascade={"persist"})
     * @Assert\Type(type="HappyR\LocationBundle\Entity\City", message="location.form.error.city")
     */
    protected $city;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Municipality", cascade={"persist"})
     */
    protected $municipality;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Region", cascade={"persist"})
     */
    protected $region;

    /**
     *
     * @ORM\ManyToOne(targetEntity="State", cascade={"persist"})
     */
    protected $state;

    /**
     *
     * @ORM\ManyToOne(targetEntity="ZipCode", cascade={"persist"})
     */
    protected $zipCode;

     /**
     * The address
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address='';


    /**
     *
     *
     * @ORM\Column(type="decimal", scale=6)
     */
    private $lng='';

     /**
     *
     *
     * @ORM\Column(type="decimal", scale=6)
     */
    private $lat='';

    /**
     * Removes all data associated with the location
     */
    public function clear()
    {
        $this->location='';
        $this->country=null;
        $this->city=null;
        $this->municipality=null;
        $this->region=null;
        $this->zipCode=null;
        $this->address='';
        $this->lng=null;
        $this->lat=null;
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
     *
     * @return string
     */
    public function getCountryCode()
    {
        if($this->getCountry()==null){
            //fallback
            return 'SE';
        }

        return $this->getCountry()->getCode();
    }


    /**
     *
     *
     *
     * @return array
     */
    public function getCoordinates()
    {
        return array('lng'=>$this->lng, 'lat'=>$this->lat);
    }

    /**
     *
     *
     *
     * @return bool
     */
    public function hasCoordinates()
    {
        return $this->lng!='' && $this->lat!='';
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
        if($str!=null){
            $this->location=$str;
        }
        else{
            $this->location='';
        }
    }

    /**
     *
     * @param mixed $address
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
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     *
     * @param mixed $city
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
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     *
     * @param mixed $country
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
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     *
     * @param mixed $lat
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
     * @return mixed
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     *
     * @param mixed $lng
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
     * @return mixed
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     *
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     *
     * @param mixed $municipality
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
     * @return mixed
     */
    public function getMunicipality()
    {
        return $this->municipality;
    }

    /**
     *
     * @param mixed $region
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
     * @return mixed
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     *
     * @param mixed $zipCode
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
     * @return mixed
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     *
     * @param mixed $state
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
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }




}
