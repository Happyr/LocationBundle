<?php

namespace Happyr\LocationBundle\Service;

use Geocoder\Result\Geocoded;
use Happyr\LocationBundle\Entity\BaseLocation;
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
     * Add Result to a Locations.
     *
     * @param $result
     * @param $location
     */
    public function addResultToLocation(Geocoded $result, BaseLocation $location)
    {
        $countryCode = strtoupper($result->getCountryCode());
        $streetAddress = sprintf('%s %s', $result->getStreetName(), $result->getStreetNumber());
        $location->setAddress(trim($streetAddress));

        //These are always correct
        $location->setCity($this->lm->getObject('City', $result->getCity(), $countryCode));
        $location->setCountry($this->lm->getObject('Country', $countryCode, null));
        $location->setZipCode($result->getZipcode());

        if (!$this->isSupportedCountry($countryCode)) {
            // just fetch something
            $region = $this->lm->getObject('Region', $result->getRegion(), $countryCode);
            $mun = $this->lm->getObject('Municipality', $result->getCityDistrict(), $countryCode);
        } else {
            /*
             * These can be tricky to find. These might be null.
             * We can not always be sure what the result set has in the $result->getRegion(). It might
             * be the region or it might be a county. It also depends on the geocoder.
             */
            if (null === $region = $this->lm->findOneObjectByName('Region', $result->getRegion(), $countryCode)) {
                $region = $this->lm->findOneObjectByName('Region', $result->getCounty(), $countryCode);
            }

            $possibleValues = array($result->getCityDistrict(), $result->getRegion(), $result->getCity());
            foreach ($possibleValues as $value) {
                if (null !== $mun = $this->lm->findOneMunicipalityByName($value, $countryCode)) {
                    break;
                }
            }
        }

        $location->setRegion($region);
        $location->setMunicipality($mun);

        //get the coordinates
        $location->setLat($result->getLatitude());
        $location->setLng($result->getLongitude());

        //set a nice looking string
        $location->setLocation(sprintf(
            '%s %s, %s, %s',
            $result->getStreetName(),
            $result->getStreetNumber(),
            $result->getCity() ?: $result->getRegion() ?: $result->getCounty(),
            $result->getCountry()
        ));
    }

    /**
     * Do we have Municipality.
     *
     * @param $countryCode
     *
     * @return bool
     */
    private function isSupportedCountry($countryCode)
    {
        $supported = array('SE', 'DA', 'NO');

        return in_array(strtoupper($countryCode), $supported);
    }
}
