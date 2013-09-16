<?php
namespace HappyR\LocationBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Eastit\Darwin\CommonBundle\DataFixtures\BaseFixture;

/**
 * Class LoadMunicipalityData
 *
 * @author Tobias Nyholm
 *
 *
 */
class LoadMunicipalityData extends BaseFixture
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

        $file = $this->parseYml('municipality.yml');
        $municipalities = $file['municipalities'];

        $cm=$this->container->get('darwin.location_manager')->getMunicipalityManager();

        foreach ($municipalities as $arr) {
            $obj = $cm->createMunicipality($arr['name'],$arr['code'],$arr['slug']);

            $manager->persist($obj);
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
