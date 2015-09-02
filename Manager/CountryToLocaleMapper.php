<?php

namespace Happyr\LocationBundle\Manager;

/**
 * @author Tobias Nyholm
 */
class CountryToLocaleMapper
{
    /**
     * Get the most likely locale from a ISO2 country code.
     *
     * @param string $countryCode
     *
     * @return string
     */
    public static function getLocale($countryCode)
    {
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
}
