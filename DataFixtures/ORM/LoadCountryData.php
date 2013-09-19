<?php
namespace HappyR\LocationBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;

use HappyR\LocationBundle\Entity\Country;

/**
 * Class LoadCountryData
 *
 * @author Tobias Nyholm
 *
 *
 */
class LoadCountryData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{

    /**
     *
     *
     * @param ObjectManager $manager
     *
     */
    public function load(ObjectManager $manager)
    {
        $this->basePath = dirname(__FILE__).'/../../Resources/data/';

        $file = $this->parseYml('countries.yml');
        $countries = $file['countries'];

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
