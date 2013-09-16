<?php


namespace HappyR\LocationBundle\Services;

use HappyR\Google\GeocoderBundle\Services\GeocodeService as Geocoder;

/**
 * Class GeocodeService uses the HappyRGoogleGeocoderBundle.
 *
 *
 * @author Tobias Nyholm
 *
 */
class GeocodeService implements GeocodeInterface
{
    /**
     * @var Geocoder geocoder
     *
     *
     */
    private $geocoder;

    function __construct(Geocoder $geocoder)
    {
        $this->geocoder = $geocoder;
    }

    /**
     * Geocode the address
     *
     * @param string $address
     *
     * @return mixed
     */
    public function geocode($address)
    {
        $result=$this->geocoder->geocodeAddress($address, true);

        die(print_r($result,true));

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
    }


}