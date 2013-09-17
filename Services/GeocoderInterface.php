<?php


namespace HappyR\LocationBundle\Services;


/**
 * Class GeocoderInterface
 *
 * @author Tobias Nyholm
 *
 */
interface GeocoderInterface
{
    /**
     * Geocode the address
     *
     * @param string $address
     *
     * @return mixed
     */
    public function geocode($address);
}