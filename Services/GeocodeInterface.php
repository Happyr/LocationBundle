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
     * @return mixed
     */
    public function geocode($address);
}