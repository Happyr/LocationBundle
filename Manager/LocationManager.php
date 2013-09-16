<?php

namespace HappyR\LocationBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use HappyR\LocationBundle\Entity\LocationObject;
use HappyR\LocationBundle\Services\SlugifierInterface;

/**
 *
 * All the other manager extends this class but the are not accessed by the service container.
 * This class does all the work
 *
 * @author tobias
 *
 */
class LocationManager
{
    /**
     * @var \Doctrine\Common\Persistence\ObjectManager em
     *
     *
     */
    protected $om;

    /**
     * @var \HappyR\LocationBundle\Services\SlugifierInterface slugifier
     *
     *
     */
    protected $slugifier;

    /**
     * @var string typePrefix
     *
     *
     */
    protected $typePrefix='HappyRLocationBundle:';


    /**
     * @param ObjectManager $em
     * @param SlugifierInterface $slugifier
     */
    public function __construct(ObjectManager $em, SlugifierInterface $slugifier)
    {
        $this->em=$em;
        $this->slugifier=$slugifier;
    }

    /**
     * Returns a object of $type. This will always return a object. A new object will be created if it does not exsist.
     *
     * @param string $entity must be safe. Don't let the user affect this one. Example "City", "Region"
     * @param string $name. The name of the type.
     */
    protected function getObject($entity, $name)
    {
        $entity=$this->typePrefix.$entity;
        $name=$this->beautifyName($name);
        $slug=$this->slugifier->slugify($name);

        //fetch object
        $object=$this->em->getRepository($entity)->findOneBy(array('slug'=>$slug));

        //if object is not found
        if (!$object) {
            $entityName=explode(':', $entity);
            $entityNamespace='HappyR\LocationBundle\Entity\\'.$entityName[1];

            //create
            $object=new $entityNamespace($name,$slug);

            $this->em->persist($object);
            $this->em->flush();

        }

        return $object;
    }

    /**
     * Return an object by slug.
     *
     * @param string $entity must be safe. Don't let the user affect this one. Example "City", "Region"
     * @param  String $slug
     * @return Object or Null
     */
    protected function findOneObjectBySlug($entity, $slug)
    {
        return $this->em->getRepository($entity)->findOneBy(array('slug'=>$slug));
    }

    /**
     * Return an object by name
     * This function slugifys the name and runs findOneObjectBySlug
     *
     * @param string $entity must be safe. Don't let the user affect this one. Example "City", "Region"
     * @param $name
     *
     * @return Object
     */
    protected function findOneObjectByName($entity, $name)
    {
        return $this->findOneObjectBySlug($entity,$this->slugifier->slugify($name));
    }

    /**
     * Rename a object
     *
     * @param string $entity must be safe. Don't let the user affect this one. Example "City", "Region"
     * @param LocationObject $object
     * @param String         $name
     */
    protected function renameObject($entity, LocationObject &$object, $name)
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
