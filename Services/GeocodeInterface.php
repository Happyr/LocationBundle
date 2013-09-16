<?php


namespace HappyR\LocationBundle\Services;


/**
 * Class GeocodeInterface
 *
 * @author Tobias Nyholm
 *
 */
interface GeocodeInterface
{
    /**
     * Geocode the address
     *
     * @param string $address
     *
     * @return array
     */
    public function geocode($address);
}