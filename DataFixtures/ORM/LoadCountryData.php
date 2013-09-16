<?php
namespace HappyR\LocationBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Eastit\Darwin\CommonBundle\DataFixtures\BaseFixture;

use HappyR\LocationBundle\Entity\Country;

class LoadCountryData extends BaseFixture
{

    public function load(ObjectManager $manager)
    {
        $this->basePath = $this->container->get('kernel')->getRootDir().'/../src/Eastit/Darwin/LocationBundle/Resources/data/';

        $file = $this->parseYml('countries.yml');
        $countries = $file['countries'];

        $cm=$this->container->get('darwin.location_manager')->getCountryManager();

        foreach ($countries as $code) {
            $obj = new Country($code);

            $manager->persist($obj);
        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 100;
    }
}
