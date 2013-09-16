<?php


namespace HappyR\LocationBundle\Tests\Services;

use HappyR\LocationBundle\Services\GeocodeService;


/**
 * Class GeocodeServiceTest
 *
 * @author Tobias Nyholm
 *
 */
class GeocodeServiceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Empty test
     */
    public function testNothing()
    {
        $this->assertTrue(true);
    }

    /**
     * @group live
     */
    public function testLiveData()
    {
        $geocoder=new \HappyR\Google\GeocoderBundle\Services\GeocodeService();
        $service=new GeocodeService($geocoder);

        $service->geocode('Lina Sandells plan 9, Sverige');


    }


}
