<?php

namespace Happyr\LocationBundle\Form\Events;

use Geocoder\Geocoder;
use Geocoder\GeocoderInterface;
use Geocoder\Result\Geocoded;
use Happyr\LocationBundle\Entity\Location;
use Happyr\LocationBundle\Manager\LocationManager;
use Symfony\Component\Form\FormEvent;

/**
 * Class GeocodeLocationString
 *
 * @author Tobias Nyholm
 *
 */
class GeocodeLocationString
{
    /**
     * @var \Happyr\LocationBundle\Manager\LocationManager $this ->lm
     *
     *
     */
    protected $lm;

    /**
     * @var \Geocoder\GeocoderInterface geocoder
     */
    protected $geocoder;

    /**
     * @param LocationManager $lm
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
        $result = $this->getGeocoder()->geocode($location->getLocation());

        if (!$result || $result->getLatitude()===0) {
            $location->clear();

            return;
        }

        $streetAddress = sprintf('%s %s', $result->getStreetName(), $result->getStreetNumber());
        $location->setAddress(trim($streetAddress));

        //These are always correct
        $location->setCity($this->lm->getObject('City', $result->getCity()));
        $location->setCountry($this->lm->getObject('Country', $result->getCountryCode()));
        $location->setZipCode($this->lm->getObject('ZipCode', $result->getZipcode()));

        //These can be tricky to find
        //TODO find these
        $location->setRegion($this->lm->getObject('Region', $result->getRegion()));
        $location->setMunicipality($this->lm->getObject('Municipality', $result->getCityDistrict()));

        //get the coordinates
        $location->setLat($result->getLatitude());
        $location->setLng($result->getLongitude());

        //set a nice looking string
        $location->setLocation(sprintf('%s %s, %s, %s', $result->getStreetName(), $result->getStreetNumber(), $result->getCity(), $result->getCountry()));

        return $location;
    }

    /**
     * Get the coordinates for this location
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
                '%s, %s, %s, %s, %s, %s',
                $location->getAddress(),
                $location->getMunicipality(),
                $location->getCity(),
                $location->getRegion(),
                $location->getState(),
                $location->getCountry()
            ));

        $location->setLat($result->getLatitude());
        $location->setLng($result->getLongitude());

        return $location;
    }

    /**
     *
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
}