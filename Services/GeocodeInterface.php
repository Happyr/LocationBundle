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
    public function geocode($address);
}