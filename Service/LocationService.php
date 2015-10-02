<?php

namespace Happyr\LocationBundle\Service;

use Happyr\LocationBundle\Entity\BaseLocation;
use Happyr\LocationBundle\Geocoder\GeocodedResult;
use Happyr\LocationBundle\Manager\LocationManager;

/**
 * @author Tobias Nyholm
 */
class LocationService
{
    /**
     * @var \Happyr\LocationBundle\Manager\LocationManager lm
     */
    protected $lm;

    /**
     * @param LocationManager $lm
     */
    public function __construct(LocationManager $lm)
    {
        $this->lm = $lm;
    }

    /**
     * Add GeocodedResult to a Location.
     *
     * @param GeocodedResult $result
     * @param BaseLocation   $location
     */
    public function addResultToLocation(GeocodedResult $result, BaseLocation $location)
    {
        $countryCode = strtoupper($result->getCountryCode());
        $location->setAddress($result->getAddress());
        $location->setCountry($this->lm->getObject('Country', $countryCode, null));
        $location->setCity($this->lm->getObject('City', $result->getCity(), $countryCode));
        $location->setZipCode($result->getZipcode());
        $location->setRegion($this->lm->getObject('Region', $result->getRegion(), $countryCode));
        $location->setMunicipality($this->lm->getObject('Municipality', $result->getMunicipality(), $countryCode, array(
            'conditions' => array(
                'code' => $result->getMunicipalityCode(),
            ),
        )));

        //get the coordinates
        $location->setLat($result->getLat());
        $location->setLng($result->getLng());

        //set a nice looking string
        $location->setLocation($result->getFullLocation());
    }
}
