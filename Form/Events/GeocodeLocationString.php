<?php

namespace Happyr\LocationBundle\Form\Events;

use Happyr\LocationBundle\Entity\Location;
use Happyr\LocationBundle\Geocoder\GeocoderInterface;
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
     * @var GeocoderInterface
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
     * @return bool
     */
    public function geocodeLocation(FormEvent $event)
    {
        /** @var Location $location */
        $location = $event->getData();

        $submittedData = $location->getLocation();
        $result = $this->geocoder->geocode($submittedData);

        if (!$result || $result->getLng() == null) {
            $location->clear();
            $location->setLocation($submittedData);

            return false;
        }

        $this->ls->addResultToLocation($result, $location);

        return true;
    }
}
