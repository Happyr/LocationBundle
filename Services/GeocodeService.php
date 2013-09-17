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

        $parts=$result[0]->address_components;
        $geometry=$result[0]->geometry;

        $ret= array(
            'fullLocation'=>$result[0]->formatted_address,
            'streetNumber'=>'',
            'street'=>'',
            'city'=>null,
            'country'=>null,
            'municipality'=>null,
            'region'=>null,
            'zipCode'=>null,
            'state'=>null,
            'lat'=>$geometry->location->lat,
            'lng'=>$geometry->location->lng,
        );

        foreach ($parts as $part) {
            if (in_array('street_number', $part->types)) {
                $ret['streetNumber']=$part->long_name;
            }
            elseif (in_array('route', $part->types)) {
                $ret['street']=$part->long_name;
            }
            elseif (in_array('locality', $part->types)) {
                $ret['city']=$part->long_name;
            }
            elseif (in_array('administrative_area_level_2', $part->types)) {
                if($ret['city']==null){
                    $ret['city']=$part->long_name;
                }
            }
            elseif (in_array('administrative_area_level_1', $part->types)) {
                $ret['state']=$part->long_name;
            }
            elseif (in_array('country', $part->types)) {
                $ret['country']=$part->short_name;
            }
            elseif (in_array('postal_code', $part->types)) {
                $ret['zipCode']=$part->long_name;
            }
            elseif (in_array('postal_town', $part->types)) {
                $ret['region']=$part->long_name;
            }
        }

        return $ret;

    }


}