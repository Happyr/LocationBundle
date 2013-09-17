<?php


namespace HappyR\LocationBundle\Services;


/**
 * Dummy service that does nothing
 *
 *
 * @author Tobias Nyholm
 *
 */
class GeocoderDummyService implements GeocodeInterface
{

    /**
     * Return an empty address
     *
     * @param string $address
     *
     * @return array
     */
    public function geocode($address)
    {
        return array();

    }

}