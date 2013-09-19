<?php
namespace HappyR\LocationBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;
use HappyR\LocationBundle\Entity\Municipality;

/**
 * Class LoadMunicipalityData
 *
 * @author Tobias Nyholm
 *
 *
 */
class LoadMunicipalityData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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

        $file = $this->parseYml('municipality.yml');
        $municipalities = $file['municipalities'];

        $lm=$this->container->get('happyr.location.location_manager');

        foreach ($municipalities as $arr) {
            $obj=new Municipality($arr['name'],$arr['slug']);
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
