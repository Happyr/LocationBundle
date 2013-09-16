<?php

namespace HappyR\LocationBundle\Manager;

use HappyR\LocationBundle\Entity\ZipCode;

/**
 * A manager to handle the zipCode object
 *
 *
 * @author tobias
 *
 */
class ZipCodeManager extends LocationObjectManager
{
    /**
     * This will allways return a zipCode. A new zipCode will be created if it does not exsist.
     *
     *
     * @param $name. The name of the type.
     * @param $flush, if true it will flush the new object in the database
     * @return a ZipCode object
     */
    public function getZipCode($name, $flush=true)
    {
        return $this->getObject('EastitDarwinLocationBundle:ZipCode', $name, $flush);
    }

    /**
     * Return a zipCode by slug.
     *
     * @param  String $slug
     * @return Object or Null
     */
    public function findOneBySlug($slug)
    {
        return $this->findOneObjectBySlug('EastitDarwinLocationBundle:ZipCode',$slug);
    }

    /**
     * Rename a zipCode
     *
     * @param ZipCode $object
     * @param String  $name
     */
    public function renameZipCode(ZipCode &$object, $name)
    {
        $this->renameObject('EastitDarwinLocationBundle:ZipCode', $object, $name);
    }

    /**
     * Remove a zipCode object
     *
     * @param ZipCode $object
     */
    public function removeZipCode(ZipCode &$object)
    {
        $this->removeObject($object);
    }

    /**
     * Merge two zipCode objects into one.
     * This will move all Location's references from $copy to $org
     * This will remove the copy Object.
     *
     * @param ZipCode $org
     * @param ZipCode $copy
     */
    public function mergeZipCodes(ZipCode &$org, ZipCode &$copy)
    {
        $this->mergeObjects('zipCode', $org, $copy);
    }
}
