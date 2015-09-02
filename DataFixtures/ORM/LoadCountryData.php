<?php

namespace Happyr\LocationBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Happyr\LocationBundle\Entity\Country;
use Symfony\Component\Yaml\Yaml;

/**
 * @author Tobias Nyholm
 */
class LoadCountryData extends AbstractFixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {return;
        $filePath = dirname(__FILE__).'/../../Resources/data/countries.yml';
        $contents = Yaml::parse(file_get_contents($filePath));

        $countries = $contents['countries'];

        foreach ($countries as $code) {
            $obj = new Country($code);
            $manager->persist($obj);
        }
        $manager->flush();
    }
}
