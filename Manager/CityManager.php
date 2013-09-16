<?php

namespace HappyR\LocationBundle\Manager;

use HappyR\LocationBundle\Entity\City;

/**
 * A manager to handle the city object
 *
 *
 * @author tobias
 *
 */
class CityManager extends LocationObjectManager
{
    /**
     * This will allways return a city. A new city will be created if it does not exsist.
     *
     *
     * @param $name. The name of the type.
     * @param $flush, if true it will flush the new object in the database
     * @return a City object
     */
    public function getCity($name, $flush=false)
    {
        if(strpos($name,',')!==false){
            $name=substr($name,0,strpos($name,','));
        }
        return $this->getObject('EastitDarwinLocationBundle:City', $name, $flush);
    }

    /**
     * Return a city by slug.
     *
     * @param  String $slug
     * @return Object or Null
     */
    public function findOneBySlug($slug)
    {
        return $this->findOneObjectBySlug('EastitDarwinLocationBundle:City',$slug);
    }

    /**
     * Rename a city
     *
     * @param City   $object
     * @param String $name
     */
    public function renameCity(City &$object, $name)
    {
        $this->renameObject('EastitDarwinLocationBundle:City', $object, $name);
    }

    /**
     * Remove a city object
     *
     * @param City $object
     */
    public function removeCity(City &$object)
    {
        $this->removeObject($object);
    }

    /**
     * Merge two city objects into one.
     * This will move all Location's references from $copy to $org
     * This will remove the copy Object.
     *
     * @param City $org
     * @param City $copy
     */
    public function mergeCities(City &$org, City &$copy)
    {
        $this->mergeObjects('city', $org, $copy);
    }
}
