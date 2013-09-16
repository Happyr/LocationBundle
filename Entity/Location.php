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
     * @ORM\ManyToOne(targetEntity="Country")
     * @Assert\Type(type="HappyR\LocationBundle\Entity\Country", message="location.form.error.country")
     */
    protected $country;

    /**
     *
     * @ORM\ManyToOne(targetEntity="City")
     * @Assert\Type(type="HappyR\LocationBundle\Entity\City", message="location.form.error.city")
     */
    protected $city;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Municipality")
     */
    protected $municipality;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Region")
     */
    protected $region;

    /**
     *
     * @ORM\ManyToOne(targetEntity="ZipCode")
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
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $lng='';

     /**
     *
     *
     * @ORM\Column(type="string", length=255, nullable=false)
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
        $this->lng='';
        $this->lat='';
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
     * Set address
     *
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set country
     *
     * @param HappyR\LocationBundle\Entity\Country $country
     */
    public function setCountry( $country)
    {
        $this->country = $country;
    }

    /**
     * Get country
     *
     * @return HappyR\LocationBundle\Entity\Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set city
     *
     * @param HappyR\LocationBundle\Entity\City $city
     */
    public function setCity( $city)
    {
        $this->city = $city;
    }

    /**
     * Get city
     *
     * @return HappyR\LocationBundle\Entity\City
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set municipality
     *
     * @param HappyR\LocationBundle\Entity\Municipality $municipality
     */
    public function setMunicipality( $municipality)
    {
        $this->municipality = $municipality;
    }

    /**
     * Get municipality
     *
     * @return HappyR\LocationBundle\Entity\Municipality
     */
    public function getMunicipality()
    {
        return $this->municipality;
    }

    /**
     * Set region
     *
     * @param HappyR\LocationBundle\Entity\Region $region
     */
    public function setRegion( $region)
    {
        $this->region = $region;
    }

    /**
     * Get region
     *
     * @return HappyR\LocationBundle\Entity\Region
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set zipCode
     *
     * @param HappyR\LocationBundle\Entity\ZipCode $zipCode
     */
    public function setZipCode( $zipCode)
    {
        $this->zipCode = $zipCode;
    }

    /**
     * Get zipCode
     *
     * @return HappyR\LocationBundle\Entity\ZipCode
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    public function getCoordinates()
    {
        return array('lng'=>$this->lng, 'lat'=>$this->lat);
    }

    public function hasCoordinates()
    {
        return $this->lng!='' && $this->lat!='';
    }

    /**
     * alias for getCoordinates
     */
    public function getCoords()
    {
        return $this->getCoordinates();
    }

    public function setCoordLong($long)
    {
        $this->lng=$long;
    }

    public function setCoordLat($lat)
    {
        $this->lat=$lat;
    }

     public function getCoordLong()
     {
        return $this->lng;
    }

    public function getCoordLat()
    {
        return $this->lat;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function setLocation($str)
    {
        if($str!=null){
            $this->location=$str;
        }
        else{
            $this->location='';
        }
    }

}
