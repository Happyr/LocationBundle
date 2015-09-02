<?php

namespace Happyr\LocationBundle\Form\Events;

use Geocoder\Geocoder;
use Geocoder\GeocoderInterface;
use Geocoder\Result\Geocoded;
use Happyr\LocationBundle\Entity\BaseLocation;
use Happyr\LocationBundle\Entity\Location;
use Happyr\LocationBundle\Manager\LocationManager;
use Happyr\LocationBundle\Service\LocationService;
use Symfony\Component\Form\FormEvent;

/**
 * @author Tobias Nyholm
 */
class GeocodeLocationString
{
    /**
     * @var LocationService ls
     */
    protected $ls;

    /**
     * @var \Geocoder\GeocoderInterface geocoder
     */
    protected $geocoder;

    /**
     * @param LocationService   $ls
     * @param GeocoderInterface $geocoder
     */
    public function __construct(LocationService $ls, GeocoderInterface $geocoder)
    {
        $this->geocoder = $geocoder;
        $this->ls = $ls;
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

        $this->ls->addResultToLocation($result, $location);

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


}
