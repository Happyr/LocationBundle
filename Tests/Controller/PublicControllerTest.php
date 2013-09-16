<?php

namespace HappyR\LocationBundle\Tests\Controller;

use Eastit\Darwin\CommonBundle\Tests\WebTestCase;

class PublicControllerTest extends WebTestCase
{

    /**
     * @group response200
     */
    public function testShowAction()
    {
        $city=$this->getCity();
        $this->getPage($this->getUrl('_public_city_show',array('slug'=>$city->getSlug())));
    }

    /**
     * @group response200
     */
    public function testFeedAction()
    {
        $city=$this->getCity();
        $this->getPage($this->getUrl('_public_city_feed',array('slug'=>$city->getSlug())));
    }

    protected function getCity()
    {
        $cm=$this->get('darwin.location_manager')->getCityManager();

        return $cm->findOneBySlug('stockholm');

    }

}
