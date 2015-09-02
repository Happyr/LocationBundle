<?php

namespace Happyr\LocationBundle\Service;

use Bazinga\Bundle\GeocoderBundle\Geocoder\LoggableGeocoder;
use Geocoder\Result\Geocoded;
use Geocoder\Result\ResultInterface;

/**
 * @author Tobias Nyholm
 */
class ExcessiveGeocoder extends LoggableGeocoder
{
    /**
     * Geocode a given value.
     *
     * @param string $value A value to geocode.
     *
     * @return ResultInterface A ResultInterface result object.
     */
    public function geocode($value)
    {
        $result = parent::geocode($value);
        $this->updateProvidersLocale($result);

        return parent::geocode($value);
    }

    /**
     * Reverse geocode given latitude and longitude values.
     *
     * @param float $latitude  Latitude.
     * @param float $longitude Longitude.
     *
     * @return ResultInterface A ResultInterface result object.
     */
    public function reverse($latitude, $longitude)
    {
        $result = parent::reverse($latitude, $longitude);
        $this->updateProvidersLocale($result);

        return parent::reverse($latitude, $longitude);
    }

    private function getLocaleFromResult(Geocoded $result)
    {
        $countryCode = $result->getCountryCode();

        switch ($countryCode) {
            case 'SE':
                return 'sv';
            case 'RU':
                return 'ru';
            case 'DA':
                return 'da';
            default:
                return 'en';
        }
    }

    /**
     * @param $result
     */
    private function updateProvidersLocale($result)
    {
        $locale = $this->getLocaleFromResult($result);

        //set the locale to the providers
        $providers = $this->getProviders();
        foreach ($providers as $p) {
            if (method_exists($p, 'setLocale')) {
                $p->setLocale($locale);
            }
        }
    }
}
