<?php

namespace Happyr\LocationBundle\Command;

use HappyR\SlugifyBundle\Services\SlugifyService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Yaml\Yaml;

/**
 * @author Tobias Nyholm
 *
 * Convert covert CSV files to YML
 */
class ConvertMunicipalityCsvToYamlCommand extends ContainerAwareCommand
{
    /**
     * @var SlugifyService slugifier
     */
    private $slugifier;

    /**
     * Configure the command.
     */
    protected function configure()
    {
        $this->setName('happyr:location:convert-files')
            ->setDescription('Convert municipality CSV files to YML.');
    }

    /**
     * Execute!
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->slugifier = $this->getContainer()->get('happyr.slugify.slugifier');

        $this->convertMunicipalities();
        $this->convertRegions();
    }

    /**
     * Input a CSV file and get a two dimentional array back.
     *
     * @param SplFileInfo $file
     *
     * @return array
     */
    protected function parseCSV(SplFileInfo $file)
    {
        $result = array();
        $rows = explode("\n", $file->getContents());
        foreach ($rows as $row) {
            $result[] = explode(';', $row);
        }

        return $result;
    }

    protected function convertMunicipalities()
    {
        $basePath = dirname(__FILE__) . '/../Resources/data/Municipalities';
        $finder = new Finder();
        $finder->files()->in($basePath)->name('*.csv');

        /** @var \Symfony\Component\Finder\SplFileInfo $file */
        foreach ($finder as $file) {
            $rows = $this->parseCSV($file);
            $yaml = array();

            foreach ($rows as $row) {
                $item = array();
                $item['code'] = $row[0];
                $item['name'] = $row[1];
                $item['slug'] = $this->slugifier->slugify($row[1]);
                $yaml[] = $item;
            }

            $yaml = Yaml::dump(array('municipalities' => $yaml), 2, 2);
            file_put_contents(str_replace('.csv', '.yml', $file->getRealPath()), $yaml);
        }
    }

    protected function convertRegions()
    {
        $basePath = dirname(__FILE__) . '/../Resources/data/Regions';
        $finder = new Finder();
        $finder->files()->in($basePath)->name('*.csv');

        /** @var \Symfony\Component\Finder\SplFileInfo $file */
        foreach ($finder as $file) {
            $rows = $this->parseCSV($file);
            $yaml = array();

            foreach ($rows as $row) {
                $item = array();
                $item['name'] = $row[0];
                $item['slug'] = $this->slugifier->slugify($row[0]);
                $yaml[] = $item;
            }

            $yaml = Yaml::dump(array('regions' => $yaml), 2, 2);
            file_put_contents(str_replace('.csv', '.yml', $file->getRealPath()), $yaml);
        }
    }
}
