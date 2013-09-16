<?php

namespace HappyR\LocationBundle\Manager;

use HappyR\LocationBundle\Entity\LocationObject;

/**
 *
 * All the other manager extends this class but the are not accessed by the service container.
 * This class does all the work
 *
 * @author tobias
 *
 */
class LocationObjectManager
{
    protected $em;
    protected $slugifier;

    public function __construct($em,$slugifier)
    {
        $this->em=$em;
        $this->slugifier=$slugifier;
    }

    /**
     * Returns a object of $type. This will allways return a object. A new object will be created if it does not exsist.
     *
     * @param $type must be safe. Dont let the user affect this one AppAcmeBundle:Name
     * @param $name. The name of the type.
     * @param $flush, if true it will flush the new object in the database
     */
    protected function getObject($type, $name, $flush=false)
    {
        $name=$this->beautifyName($name);
        $slug=$this->slugifier->slugify($name);

        $object=$this->em->getRepository($type)->findOneBy(array('slug'=>$slug));

        if (!$object) {
            $entityName=explode(':', $type);
            $entityNamespace='HappyR\LocationBundle\Entity\\'.$entityName[1];

            //create
            $object=new $entityNamespace($name,$slug);
            $this->em->persist($object);
            if($flush)
                $this->em->flush();

        }

        return $object;
    }

    /**
     * Return an object by slug.
     *
     * @param $type must be safe. Dont let the user affect this one AppAcmeBundle:Name
     * @param  String $slug
     * @return Object or Null
     */
    protected function findOneObjectBySlug($type, $slug)
    {
        return $this->em->getRepository($type)->findOneBy(array('slug'=>$slug));
    }

    /**
     * Return an object by name
     * This function slugifys the name and runs findOneObjectBySlug
     */
    protected function findOneObjectByName($type, $name)
    {
        return $this->findOneObjectBySlug($type,$this->slugifier->slugify($name));
    }

    /**
     * Rename a object
     *
     * @param $type must be safe. Dont let the user affect this one AppAcmeBundle:Name
     * @param LocationObject $object
     * @param String         $name
     */
    protected function renameObject($type, LocationObject &$object, $name)
    {
        $name=$this->beautifyName($name);
        $object->setName($name);
        $object->setSlug($this->slugifier->slugify($name));

        $this->em->persist($object);
        $this->em->flush();
    }

    /**
     * Remove a Location object
     *
     * @param LocationObject $object
     */
    protected function removeObject(LocationObject &$object)
    {
        $this->em->remove($object);
        $this->em->flush();
    }

    /**
     * Merge two Location Objects into one.
     * This will move all Location's references from $copy to $org
     * This will remove the copy Object.
     *
     * @param String         $databaseName. The $databaseName will be inserted in a query. Must be safe
     * @param LocationObject $org
     * @param LocationObject $copy
     */
    protected function mergeObjects($databaseName, LocationObject &$org, LocationObject &$copy)
    {
        $this->em->createQuery('UPDATE EastitLegoLocationBundle:Location e SET e.'.$databaseName.'=:org_id WHERE e.'.$databaseName.'=:copy_id')
            ->setParameter('org_id',$org->getId())
            ->setParameter('copy_id',$copy->getId())
            ->execute();

        $this->removeObject($copy);

    }

    /**
     * Makes name beautiful before using it.
     *
     * @param String $name
     */
    protected function beautifyName($name)
    {
        return mb_convert_case(mb_strtolower(trim($name),'UTF-8'), MB_CASE_TITLE, 'UTF-8');
    }

}
