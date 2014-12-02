<?php

namespace Happyr\LocationBundle\Form\Events;

use Geocoder\GeocoderInterface;
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
        $result = $this->geocoder->geocode($location->getLocation());

        if (!$result) {
            $location->clear();

            return;
        }

        $defaults = array(
            'fullLocation' => '',
            'streetNumber' => '',
            'street' => '',
            'city' => null,
            'country' => null,
            'municipality' => null,
            'region' => null,
            'zipCode' => null,
            'state' => null,
            'lat' => 0,
            'lng' => 0,
        );

        //merge the result with the defaults
        $result += $defaults;

        $streetAddress = $result['street'] . ' ' . $result['streetNumber'];
        $location->setAddress(trim($streetAddress));

        $location->setCity($this->lm->getObject('City', $result['city']));
        $location->setCountry($this->lm->getObject('Country', $result['country']));
        $location->setZipCode($this->lm->getObject('ZipCode', $result['zipCode']));
        $location->setRegion($this->lm->getObject('Region', $result['region']));
        $location->setMunicipality($this->lm->getObject('Municipality', $result['municipality']));
        $location->setState($this->lm->getObject('State', $result['state']));

        $location->setLat($result['lat']);
        $location->setLng($result['lng']);
        $location->setLocation($result['fullLocation']);

        $event->setData($location);

        return $location;
    }
}