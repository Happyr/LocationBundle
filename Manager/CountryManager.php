<?php

namespace HappyR\LocationBundle\Manager;

use HappyR\LocationBundle\Entity\Country;

/**
 * A manager to handle the country object
 *
 *
 * @author tobias
 *
 */
class CountryManager extends LocationObjectManager
{
    /**
     * Return a country by its contry code
     *
     *
     *  @param String $slug
     * @return Object or Null
     */
    public function getCountry($name)
    {
        return $this->findOneObjectByName('EastitDarwinLocationBundle:Country', $name);
    }

    /**
     * Return a country by slug.
     *
     * @param  String $slug
     * @return Object or Null
     */
    public function findOneBySlug($slug)
    {
        return $this->findOneObjectBySlug('EastitDarwinLocationBundle:Country',$slug);
    }

    /**
     * Rename a country
     *
     * @param Country $object
     * @param String  $name
     */
    public function renameCountry(Country &$object, $name)
    {
        $this->renameObject('EastitDarwinLocationBundle:Country', $object, $name);
    }

    /**
     * Remove a country object
     *
     * @param Country $object
     */
    public function removeCountry(Country &$object)
    {
        $this->removeObject($object);
    }

    /**
     * Merge two country objects into one.
     * This will move all Location's references from $copy to $org
     * This will remove the copy Object.
     *
     * @param Country $org
     * @param Country $copy
     */
    public function mergeCountries(Country &$org, Country &$copy)
    {
        $this->mergeObjects('country', $org, $copy);
    }
}
