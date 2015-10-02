<?php

namespace Happyr\LocationBundle\Geocoder;

class GeocodedResult
{
    /**
     * @var string
     */
    private $country;

    /**
     * @var string
     */
    private $countryCode;

    /**
     * @var string
     */
    private $city;

    /**
     * @var string
     */
    private $municipality;

    /**
     * @var string
     */
    private $municipalityCode;

    /**
     * @var string
     */
    private $region;

    /**
     * @var string
     */
    private $zipCode;

    /**
     * @var string
     */
    private $address;

    /**
     * @var float
     */
    private $lat;

    /**
     * @var float
     */
    private $lng;

    /**
     * @var string
     */
    private $fullLocation;

    /**
     * @param $array
     *
     * @return GeocodedResult
     */
    static function createFromArray($input)
    {
        $defaults = self::getResultParts();

        $array = array_merge($defaults, $input);

        $result = new GeocodedResult();
        $result
            ->setCountry($array['country'])
            ->setCountryCode($array['countryCode'])
            ->setCity($array['city'])
            ->setMunicipality($array['municipality'])
            ->setMunicipalityCode($array['municipalityCode'])
            ->setRegion($array['region'])
            ->setZipCode($array['zipCode'])
            ->setAddress($array['address'])
            ->setLat($array['lat'])
            ->setLng($array['lng'])
            ->setFullLocation($array['fullLocation']);

        return $result;
    }

    /**
     *
     * @return array
     */
    static function getResultParts()
    {
        $defaults = [
            'country' => null,
            'countryCode' => null,
            'city' => null,
            'municipality' => null,
            'municipalityCode' => null,
            'region' => null,
            'zipCode' => null,
            'address' => null,
            'lat' => null,
            'lng' => null,
            'fullLocation' => null,
        ];

        return $defaults;
    }

    /**
     * @param $name
     *
     * @return mixed
     */
    public function get($name)
    {
        $parts = array_keys(self::getResultParts());
        if (!in_array($name, $parts)) {
            throw new \InvalidArgumentException(sprintf('Could not find property "%s" on %s', $name, get_class($this)));
        }

        return $this->$name;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     *
     * @return GeocodedResult
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return string
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * @param string $countryCode
     *
     * @return GeocodedResult
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     *
     * @return GeocodedResult
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return string
     */
    public function getMunicipality()
    {
        return $this->municipality;
    }

    /**
     * @param string $municipality
     *
     * @return GeocodedResult
     */
    public function setMunicipality($municipality)
    {
        $this->municipality = $municipality;

        return $this;
    }

    /**
     * @return string
     */
    public function getMunicipalityCode()
    {
        return $this->municipalityCode;
    }

    /**
     * @param string $municipalityCode
     *
     * @return GeocodedResult
     */
    public function setMunicipalityCode($municipalityCode)
    {
        $this->municipalityCode = $municipalityCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param string $region
     *
     * @return GeocodedResult
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * @return string
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * @param string $zipCode
     *
     * @return GeocodedResult
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;

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
     * @param string $address
     *
     * @return GeocodedResult
     */
    public function setAddress($address)
    {
        $this->address = $address;

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
     * @param float $lat
     *
     * @return GeocodedResult
     */
    public function setLat($lat)
    {
        $this->lat = $lat;

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
     * @param float $lng
     *
     * @return GeocodedResult
     */
    public function setLng($lng)
    {
        $this->lng = $lng;

        return $this;
    }

    /**
     * @return string
     */
    public function getFullLocation()
    {
        return $this->fullLocation;
    }

    /**
     * @param string $fullLocation
     *
     * @return GeocodedResult
     */
    public function setFullLocation($fullLocation)
    {
        $this->fullLocation = $fullLocation;

        return $this;
    }
}