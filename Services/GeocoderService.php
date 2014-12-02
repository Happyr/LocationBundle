<?php


namespace Happyr\LocationBundle\Services;

use HappyR\Google\GeocoderBundle\Services\GeocodeService as Geocoder;

/**
 * Class GeocoderService uses the HappyRGoogleGeocoderBundle.
 *
 *
 * @author Tobias Nyholm
 *
 */
class GeocoderService implements GeocoderInterface
{
    /**
     * @var Geocoder geocoder
     *
     *
     */
    private $geocoder;

    /**
     * @param Geocoder $geocoder
     */
    public function __construct(Geocoder $geocoder)
    {
        $this->geocoder = $geocoder;
    }

    /**
     * Geocode the address
     *
     * @param string $address
     *
     * @return array
     */
    public function geocode($address)
    {
        $importantValues = $this->getImportantValues();
        $result = $this->geocoder->geocodeAddress($address, true);

        if ($result == null) {
            return array();
        }

        $parts = $result[0]->address_components;
        $geometry = $result[0]->geometry;

        $ret = array(
            'fullLocation' => $result[0]->formatted_address,
            'streetNumber' => '',
            'street' => '',
            'city' => null,
            'country' => null,
            'municipality' => null,
            'region' => null,
            'zipCode' => null,
            'state' => null,
            'lat' => $geometry->location->lat,
            'lng' => $geometry->location->lng,
        );

        $this->extractData($ret, $parts, $importantValues);

        if (count($importantValues) > 0) {
            $this->reverseLookup($ret, $importantValues);
        }

        return $ret;
    }

    /**
     * We do a reverse look up to get more information about the address.
     *
     * @param array &$params
     * @param array &$importantValues
     *
     */
    protected function reverseLookup(array &$params, array &$importantValues)
    {
        if (null === $results = $this->geocoder->reverseGeocodeAddress($params['lat'], $params['lng'], true)) {
            return;
        }

        foreach ($results as $result) {
            $this->extractData($params, $result->address_components, $importantValues);

            if (count($importantValues) == 0) {
                break;
            }
        }
    }

    /**
     *
     * Extract data from result
     *
     *
     * @param array &$ret
     * @param array &$parts
     * @param array &$importantValues
     *
     */
    protected function extractData(array &$ret, array &$parts, array &$importantValues)
    {
        foreach ($parts as $part) {
            if (isset($importantValues['streetNumber']) && in_array('street_number', $part->types)) {
                $ret['streetNumber'] = $part->long_name;
                unset($importantValues['streetNumber']);
            } elseif (isset($importantValues['street']) && in_array('route', $part->types)) {
                $ret['street'] = $part->long_name;
                unset($importantValues['street']);
            } elseif (isset($importantValues['city']) && in_array('locality', $part->types)) {
                $ret['city'] = $part->long_name;
                unset($importantValues['city']);
            } elseif (isset($importantValues['city']) && in_array('administrative_area_level_2', $part->types)) {
                if ($ret['city'] == null) {
                    $ret['city'] = $part->long_name;
                    unset($importantValues['city']);
                }
            } elseif (isset($importantValues['state']) && in_array('administrative_area_level_1', $part->types)) {
                $ret['state'] = $part->long_name;
                unset($importantValues['state']);
            } elseif (isset($importantValues['country']) && in_array('country', $part->types)) {
                $ret['country'] = $part->short_name;
                unset($importantValues['country']);
            } elseif (isset($importantValues['zipCode']) && in_array('postal_code', $part->types)) {
                $ret['zipCode'] = $part->long_name;
                unset($importantValues['zipCode']);
            } elseif (isset($importantValues['region']) && in_array('postal_town', $part->types)) {
                $ret['region'] = $part->long_name;
                unset($importantValues['region']);
            } elseif (isset($importantValues['municipality']) && in_array('locality', $part->types)) {
                $ret['municipality'] = $part->long_name;
                unset($importantValues['municipality']);
            }
        }
    }

    /**
     * Get values that we think that we should have
     *
     * @return array
     */
    protected function getImportantValues()
    {
        return array(
            'streetNumber' => true,
            'street' => true,
            'city' => true,
            'country' => true,
            'municipality' => true,
            'region' => true,
            'zipCode' => true,
            'state' => true,
        );
    }
}