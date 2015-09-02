<?php

namespace Happyr\LocationBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Happyr\LocationBundle\Entity\Region;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

/**
 * @author Tobias Nyholm
 */
class LoadRegionData extends AbstractFixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $basePath = dirname(__FILE__).'/../../Resources/data/Regions';

        $finder = new Finder();
        $finder->files()->in($basePath)->name('*.yml');

        /** @var \Symfony\Component\Finder\SplFileInfo $file */
        foreach ($finder as $file) {
            $country = substr($file->getFilename(), 0, -4);
            $contents = Yaml::parse($file->getContents());
            $municipalities = $contents['regions'];

            foreach ($municipalities as $arr) {
                $obj = new Region($arr['name'], $arr['slug'], $country);

                $manager->persist($obj);
            }
            $manager->flush();
        }
    }
}
