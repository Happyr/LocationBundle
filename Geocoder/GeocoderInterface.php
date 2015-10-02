<?php

namespace Happyr\LocationBundle\Geocoder;

interface GeocoderInterface
{
    /**
     * @param string $str
     *
     * @return GeocodedResult
     */
    public function geocode($str);
}
