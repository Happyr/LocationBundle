<?php
namespace Happyr\LocationBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Happyr\LocationBundle\DataFixtures\BaseFixture;
use Happyr\LocationBundle\Entity\Municipality;

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
        $this->basePath = dirname(__FILE__) . '/../../Resources/data/';

        $file = $this->parseYml('municipality.yml');
        $municipalities = $file['municipalities'];

        foreach ($municipalities as $arr) {
            $obj = new Municipality($arr['name'], $arr['slug']);
            $obj->setCode($arr['code']);

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
