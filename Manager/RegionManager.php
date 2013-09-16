<?php

namespace HappyR\LocationBundle\Manager;

use HappyR\LocationBundle\Entity\Region;

/**
 * A manager to handle the region object
 *
 *
 * @author tobias
 *
 */
class RegionManager extends LocationObjectManager
{
    /**
     * This will allways return a region. A new region will be created if it does not exsist.
     *
     *
     * @param $name. The name of the type.
     * @param $flush, if true it will flush the new object in the database
     * @return a Region object
     */
    public function getRegion($name, $flush=false)
    {
        return $this->getObject('EastitDarwinLocationBundle:Region', $name, $flush);
    }

    /**
     * Return a region by slug.
     *
     * @param  String $slug
     * @return Object or Null
     */
    public function findOneBySlug($slug)
    {
        return $this->findOneObjectBySlug('EastitDarwinLocationBundle:Region',$slug);
    }

    /**
     * Rename a region
     *
     * @param Region $object
     * @param String $name
     */
    public function renameRegion(Region &$object, $name)
    {
        $this->renameObject('EastitDarwinLocationBundle:Region', $object, $name);
    }

    /**
     * Remove a region object
     *
     * @param Region $object
     */
    public function removeRegion(Region &$object)
    {
        $this->removeObject($object);
    }

    /**
     * Merge two region objects into one.
     * This will move all Location's references from $copy to $org
     * This will remove the copy Object.
     *
     * @param Region $org
     * @param Region $copy
     */
    public function mergeRegions(Region &$org, Region &$copy)
    {
        $this->mergeObjects('region', $org, $copy);
    }

}
