<?php

namespace HappyR\LocationBundle\Form\Events;
use HappyR\LocationBundle\Manager\LocationManager;
use HappyR\LocationBundle\Services\GeocodeInterface;
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
     * @var \HappyR\LocationBundle\Manager\LocationManager $this->lm
     *
     *
     */
    protected $lm;

    /**
     * @var \HappyR\LocationBundle\Services\GeocodeInterface $geocoder
     *
     *
     */
    protected $geocoder;


    /**
     * @param LocationManager $lm
     * @param GeocodeInterface $geocoder
     */
    function __construct(LocationManager $lm, GeocodeInterface $geocoder)
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
        $location=$event->getData();
        $addressObject=$this->geocoder->geocode($location->getLocationStr(),true);
        if(!$addressObject){
            return;
        }

        $addressParts=$addressObject[0]->address_components;
        $streetAddress='';

        $location->clear();

        //parse through the address components
        foreach ($addressParts as $addressPart) {
            if (in_array('street_number', $addressPart->types)) {
                $streetAddress.=' '.$addressPart->long_name;
            }
            elseif (in_array('route', $addressPart->types)) {
                $streetAddress=$addressPart->long_name.$streetAddress;
            }
            elseif (in_array('locality', $addressPart->types)) {
                $location->setCity($this->lm->getObject('City',$addressPart->long_name));
            }
            elseif (in_array('administrative_area_level_2', $addressPart->types)) {
                if($location->getCity()==null){
                    $location->setCity($this->lm->getObject('City', $addressPart->long_name));
                }
            }
            elseif (in_array('country', $addressPart->types)) {
                $location->setCountry($this->lm->getObject('Country', $addressPart->short_name));
            }
            elseif (in_array('postal_code', $addressPart->types)) {
                $location->setZipCode($this->lm->getObject('ZipCode', $addressPart->long_name));
            }
            elseif (in_array('postal_town', $addressPart->types)) {
                $location->setRegion($this->lm->getObject('Region', $addressPart->long_name));
            }

        }

        $location->setCoordLong($addressObject[0]->geometry->location->lng);
        $location->setCoordLat($addressObject[0]->geometry->location->lat);
        $location->setLocation($addressObject[0]->formatted_address);
        $location->setAddress($streetAddress);

        $event->setData($location);

        return $location;
    }

}