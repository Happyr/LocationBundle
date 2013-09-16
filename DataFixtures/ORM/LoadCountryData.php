<?php
namespace HappyR\LocationBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Eastit\Darwin\CommonBundle\DataFixtures\BaseFixture;

use HappyR\LocationBundle\Entity\Country;

/**
 * Class LoadCountryData
 *
 * @author Tobias Nyholm
 *
 *
 */
class LoadCountryData extends BaseFixture
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

        $file = $this->parseYml('countries.yml');
        $countries = $file['countries'];

        $cm=$this->container->get('darwin.location_manager')->getCountryManager();

        foreach ($countries as $code) {
            $obj = new Country($code);

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
