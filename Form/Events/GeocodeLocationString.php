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
        $result=$this->geocoder->geocode($location->getLocationStr());
        if(!$result){
            return;
        }

        $defaults=array(
            'fullLocation'=>'',
            'streetNumber'=>'',
            'street'=>'',
            'city'=>null,
            'country'=>null,
            'municipality'=>null,
            'region'=>null,
            'zipCode'=>null,
            'state'=>null,
            'lat'=>null,
            'lng'=>null,
        );

        //merge the result with the defaults
        $result+=$defaults;

        $streetAddress=$result['street'].' '.$result['streetNumber'];
        $location->setAddress(trim($streetAddress));

        $location->setCity($this->lm->getObject('City', $result['city']));
        $location->setCountry($this->lm->getObject('Country', $result['country']));
        $location->setZipCode($this->lm->getObject('ZipCode', $result['zipCode']));
        $location->setRegion($this->lm->getObject('Region', $result['region']));
        $location->setMunicipality($this->lm->getObject('Municipality', $result['municipality']));
        $location->setState($this->lm->getObject('State', $result['state']));

        $location->setCoordLat($result['lat']);
        $location->setCoordLong($result['lng']);
        $location->setLocation($result['fullLocation']);

        $event->setData($location);

        return $location;
    }

}