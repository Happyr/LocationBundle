<?php

namespace HappyR\LocationBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use HappyR\LocationBundle\Entity\Component;
use HappyR\LocationBundle\Entity\LocationObject;
use HappyR\LocationBundle\Services\SlugifierInterface;
use HappyR\SlugifyBundle\Services\SlugifyService;


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
    public function __construct(ObjectManager $em, SlugifyService $slugifier)
    {
        $this->em=$em;
        $this->slugifier=$slugifier;
    }

    /**
     * Returns a object of $type. This will always return a object. A new object will be created if it does not exsist.
     *
     * @param string $entity must be safe. Don't let the user affect this one. Example "City", "Region"
     * @param string $name The name of the type.
     *
     * @return mixed
     */
    public function getObject($entity, $name)
    {
        if($name==null){
            return null;
        }

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
        }

        return $object;
    }

    /**
     * Return an object by slug.
     *
     * @param string $entity must be safe. Don't let the user affect this one. Example "City", "Region"
     * @param string $slug
     * @return mixed|null
     */
    public function findOneObjectBySlug($entity, $slug)
    {
        $entity=$this->typePrefix.$entity;

        return $this->em->getRepository($entity)->findOneBy(array('slug'=>$slug));
    }

    /**
     * Return an object by name
     * This function slugifys the name and runs findOneObjectBySlug
     *
     * @param string $entity must be safe. Don't let the user affect this one. Example "City", "Region"
     * @param string $name
     *
     * @return Object
     */
    public function findOneObjectByName($entity, $name)
    {
        return $this->findOneObjectBySlug($entity,$this->slugifier->slugify($name));
    }

    /**
     * Rename a object
     *
     * @param string $entity must be safe. Don't let the user affect this one. Example "City", "Region"
     * @param Component &$component
     * @param String         $name
     */
    public function renameObject($entity, Component &$component, $name)
    {
        $name=$this->beautifyName($name);
        $component->setName($name);
        $component->setSlug($this->slugifier->slugify($name));

        $this->em->persist($component);
        $this->em->flush();
    }

    /**
     * Remove a Location object
     *
     * @param Component &$component
     */
    public function removeObject(Component &$component)
    {
        $this->em->remove($component);
        $this->em->flush();
    }

    /**
     * Merge two Location Objects into one.
     * This will move all Location's references from $copy to $org
     * This will remove the copy Object.
     *
     * @param String $databaseName  The $databaseName will be inserted in a query. Must be safe
     * @param Component &$org
     * @param Component &$copy
     */
    public function mergeObjects($databaseName, Component &$org, Component &$copy)
    {
        $this->em->createQuery('UPDATE EastitLegoLocationBundle:Location e SET e.'
                .$databaseName.'=:org_id WHERE e.'.$databaseName.'=:copy_id')
            ->setParameter('org_id',$org->getId())
            ->setParameter('copy_id',$copy->getId())
            ->execute();

        $this->removeObject($copy);

    }

    /**
     * Makes name beautiful before using it.
     *
     * @param string $name
     *
     * @return string
     */
    protected function beautifyName($name)
    {
        return mb_convert_case(mb_strtolower(trim($name),'UTF-8'), MB_CASE_TITLE, 'UTF-8');
    }

}
