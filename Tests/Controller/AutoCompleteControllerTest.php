<?php

namespace HappyR\LocationBundle\Tests\Controller;

use Eastit\Darwin\CommonBundle\Tests\WebTestCase;

class AutoCompleteControllerTest extends WebTestCase
{

    /**
     * @group response200
     */
    public function testCityAction()
    {
        $this->loginUser();
        $this->getPage($this->getUrl('_public_location_autocomplete_city',array('term'=>'st')));
        $this->getPage($this->getUrl('_public_location_autocomplete_city',array('term'=>'stoc')));
        $this->getPage($this->getUrl('_public_location_autocomplete_city',array('term'=>'asdagagadasgasgs')));
    }

}
