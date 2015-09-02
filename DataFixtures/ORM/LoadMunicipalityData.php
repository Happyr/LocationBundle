<?php

namespace Happyr\LocationBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Happyr\LocationBundle\Entity\Municipality;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

/**
 * @author Tobias Nyholm
 */
class LoadMunicipalityData  extends AbstractFixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $basePath = dirname(__FILE__).'/../../Resources/data/Municipalities';

        $finder = new Finder();
        $finder->files()->in($basePath)->name('*.yml');

        /** @var \Symfony\Component\Finder\SplFileInfo $file */
        foreach ($finder as $file) {
            $country = substr($file->getFilename(), 0, -4);
            $contents = Yaml::parse($file->getContents());
            $municipalities = $contents['municipalities'];

            foreach ($municipalities as $arr) {
                $obj = new Municipality($arr['name'], $arr['slug'], $country);
                $obj->setCode($arr['code']);

                $manager->persist($obj);
            }
            $manager->flush();
        }
    }
}
