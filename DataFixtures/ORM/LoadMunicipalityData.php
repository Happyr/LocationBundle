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
        $this->basePath = $this->container->get('kernel')->getRootDir().
            '/../src/Eastit/Darwin/LocationBundle/Resources/data/';

        $file = $this->parseYml('municipality.yml');
        $municipalities = $file['municipalities'];

        $lm=$this->container->get('happyr.location.location_manager');

        foreach ($municipalities as $arr) {
            $obj = $lm->get('Municipality',$arr['name'],$arr['code'],$arr['slug']);

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
