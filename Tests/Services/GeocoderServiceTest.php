<?php


namespace Happyr\LocationBundle\Tests\Services;

use HappyR\Google\GeocoderBundle\Services\ScraperService;
use Happyr\LocationBundle\Services\GeocoderService;

use Mockery as m;

/**
 * Class GeocoderServiceTest
 *
 * @author Tobias Nyholm
 *
 */
class GeocoderServiceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test teh geocode
     */
    public function testGeocode()
    {
        $address = 'Test';
        $result = $this->getFakeResponse();

        $geocoder = m::mock('HappyR\Google\GeocoderBundle\Services\GeocodeService')
            ->shouldReceive('geocodeAddress')->with($address, true)->andReturn($result)
            ->shouldReceive('reverseGeocodeAddress')->with(59.2848305, 17.9616526, true)->andReturn(array())
            ->getMock();

        $service = new GeocoderService($geocoder);

        //TODO validate the response
        $service->geocode($address);
    }

    /**
     * @group live
     */
    public function testLiveData()
    {
        $scraper = new ScraperService();
        $geocoder = new \HappyR\Google\GeocoderBundle\Services\GeocoderService($scraper);
        $service = new GeocoderService($geocoder);

        $service->geocode('Lina Sandells plan 9, Sverige');
    }

    /**
     * Return a fake response.. This is where I live btw
     *
     *
     * @return mixed
     */
    private function getFakeResponse()
    {
        return json_decode(
            '[{"address_components":[{"long_name":"9","short_name":"9","types":["street_number"]},{"long_name":"Lina Sandells plan","short_name":"Lina Sandells plan","types":["route"]},{"long_name":"Fru\u00e4ngen","short_name":"Fru\u00e4ngen","types":["sublocality","political"]},{"long_name":"Stockholm","short_name":"Stockholm","types":["locality","political"]},{"long_name":"Stockholm","short_name":"Stockholm","types":["administrative_area_level_2","political"]},{"long_name":"Stockholm County","short_name":"Stockholm County","types":["administrative_area_level_1","political"]},{"long_name":"Sweden","short_name":"SE","types":["country","political"]},{"long_name":"12953","short_name":"12953","types":["postal_code"]},{"long_name":"H\u00e4gersten","short_name":"H\u00e4gersten","types":["postal_town"]}],"formatted_address":"Lina Sandells plan 9, 129 53 H\u00e4gersten, Sweden","geometry":{"location":{"lat":59.2848305,"lng":17.9616526},"location_type":"ROOFTOP","viewport":{"northeast":{"lat":59.286179480292,"lng":17.963001580292},"southwest":{"lat":59.283481519708,"lng":17.960303619709}}},"types":["street_address"]}]'
        );
    }
}
