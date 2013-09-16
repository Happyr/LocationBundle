<?php

namespace HappyR\LocationBundle\Manager;

use HappyR\LocationBundle\Entity\Municipality;

/**
 * A manager to handle the municipality object
 *
 *
 * @author tobias
 *
 */
class MunicipalityManager extends LocationObjectManager
{
    /**
     * Return a municipality by name.
     *
     * This wont add a Municipality. We have a fixed number of Municipality.
     *
     * @param  String $slug
     * @return Object or Null
     */
    public function getMunicipality($name)
    {
        return $this->findOneObjectByName('EastitDarwinLocationBundle:Municipality', $name);
    }

    /**
     * Return a municipality by slug.
     *
     * @param  String $slug
     * @return Object or Null
     */
    public function findOneBySlug($slug)
    {
        return $this->findOneObjectBySlug('EastitDarwinLocationBundle:Municipality',$slug);
    }

    /**
     * Rename a municipality
     *
     * @param Municipality $object
     * @param String       $name
     */
    public function renameMunicipality(Municipality &$object, $name)
    {
        $this->renameObject('EastitDarwinLocationBundle:Municipality', $object, $name);
    }

    /**
     * Remove a municipality object
     *
     * @param Municipality $object
     */
    public function removeMunicipality(Municipality &$object)
    {
        $this->removeObject($object);
    }

    /**
     * Merge two municipality objects into one.
     * This will move all Location's references from $copy to $org
     * This will remove the copy Object.
     *
     * @param Municipality $org
     * @param Municipality $copy
     */
    public function mergeMunicipalities(Municipality &$org, Municipality &$copy)
    {
        $this->mergeObjects('municipality', $org, $copy);
    }


    /**
     * Creates a slug without to flash
     */
    public function createMunicipality($name, $code, $slug=null)
    {
        $obj=$this->getObject('EastitDarwinLocationBundle:Municipality', $name, false);

        if ($slug!=null) {
            $obj->setSlug($slug);
        }

        $obj->setCode($code);

        return $obj;
    }

}
