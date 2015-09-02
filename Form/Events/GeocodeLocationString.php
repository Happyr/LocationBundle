<?php

namespace Happyr\LocationBundle\Form\Events;

use Geocoder\Geocoder;
use Geocoder\GeocoderInterface;
use Geocoder\Result\Geocoded;
use Happyr\LocationBundle\Entity\BaseLocation;
use Happyr\LocationBundle\Entity\Location;
use Happyr\LocationBundle\Manager\LocationManager;
use Symfony\Component\Form\FormEvent;

/**
 * @author Tobias Nyholm
 */
class GeocodeLocationString
{
    /**
     * @var \Happyr\LocationBundle\Manager\LocationManager lm
     */
    protected $lm;

    /**
     * @var \Geocoder\GeocoderInterface geocoder
     */
    protected $geocoder;

    /**
     * @param LocationManager   $lm
     * @param GeocoderInterface $geocoder
     */
    public function __construct(LocationManager $lm, GeocoderInterface $geocoder)
    {
        $this->geocoder = $geocoder;
        $this->lm = $lm;
    }

    /**
     * Geocode location. Get as much info as we possible can.
     *
     * @param FormEvent $event
     *
     * @return mixed
     */
    public function geocodeLocation(FormEvent $event)
    {
        /** @var Location $location */
        $location = $event->getData();

        /** @var Geocoded $result */
        $submittedData = $location->getLocation();
        $result = $this->getGeocoder()->geocode($submittedData);

        if (!$result || $result->getLatitude() === 0) {
            $location->clear();
            $location->setLocation($submittedData);

            return;
        }

        $this->addLocationObjects($result, $location);

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

        return $location;
    }

    /**
     * Get the coordinates for this location.
     *
     * @param FormEvent $event
     *
     * @return Location
     */
    public function getCoordinates(FormEvent $event)
    {
        /** @var Location $location */
        $location = $event->getData();

        /** @var Geocoded $result */
        $result = $this->getGeocoder()->geocode(sprintf(
                '%s, %s, %s, %s, %s',
                $location->getAddress(),
                $location->getMunicipality(),
                $location->getCity(),
                $location->getRegion(),
                $location->getCountry()
            ));

        $location->setLat($result->getLatitude());
        $location->setLng($result->getLongitude());

        return $location;
    }

    /**
     * @return GeocoderInterface
     */
    private function getGeocoder()
    {
        if ($this->geocoder instanceof Geocoder) {
            $provider = $this->geocoder->getProviders();
            if (isset($provider['cache'])) {
                return $this->geocoder->using('cache');
            }
            if (isset($provider['chain'])) {
                return $this->geocoder->using('chain');
            }
        }

        return $this->geocoder;
    }

    /**
     * @param $result
     * @param $location
     */
    private function addLocationObjects(Geocoded $result, BaseLocation $location)
    {
        $streetAddress = sprintf('%s %s', $result->getStreetName(), $result->getStreetNumber());
        $location->setAddress(trim($streetAddress));

        //These are always correct
        $location->setCity($this->lm->getObject('City', $result->getCity()));
        $location->setCountry($this->lm->getObject('Country', $result->getCountryCode()));
        $location->setZipCode($result->getZipcode());

        if (!$this->isSupportedCountry($result->getCountryCode())) {
            // just fetch something
            $region = $this->lm->getObject('Region', $result->getRegion());
            $mun = $this->lm->getObject('Municipality', $result->getCityDistrict());
        } else {
            /*
             * These can be tricky to find. These might be null.
             * We can not always be sure what the result set has in the $result->getRegion(). It might
             * be the region or it might be a county. It also depends on the geocoder.
             */
            if (null === $region = $this->lm->findOneObjectByName('Region', $result->getRegion())) {
                $region = $this->lm->findOneObjectByName('Region', $result->getCounty());
            }

            if (null === $mun = $this->lm->findOneObjectByName('Municipality', $result->getCityDistrict())) {
                $mun = $this->lm->findOneObjectByName('Municipality', $result->getCity());
            }
        }

        $location->setRegion($region);
        $location->setMunicipality($mun);
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
        $supported = array('SV', 'DA', 'NO');

        return in_array(strtoupper($countryCode), $supported);
    }
}
