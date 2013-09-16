<?php
namespace HappyR\LocationBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Eastit\Darwin\CommonBundle\DataFixtures\BaseFixture;

use HappyR\LocationBundle\Entity\City;

/**
 * Class LoadCityData
 *
 * @author Tobias Nyholm
 *
 *
 */
class LoadCityData extends BaseFixture
{
    /**
     *
     *
     * @param ObjectManager $manager
     *
     */
    public function load(ObjectManager $manager)
    {
        $this->basePath = $this->container->get('kernel')->getRootDir().'/../src/Eastit/Darwin/LocationBundle/Resources/data/';

        $file = $this->parseYml('cities.yml');
        $cities = $file['cities'];

        $cm=$this->container->get('darwin.location_manager')->getCityManager();

        foreach ($cities as $cityName) {
            $obj = $cm->getCity($cityName);

            $manager->persist($obj);
            $this->addReference($obj->getName(),'city', $obj);
        }
        $manager->flush();
    }


    /**
     *
     *
     *
     * @return int
     */
    public function getOrder()
    {
        return 100;
    }
}
