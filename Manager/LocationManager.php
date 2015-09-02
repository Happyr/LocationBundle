<?php

namespace Happyr\LocationBundle\Manager;

use Doctrine\ORM\EntityManager;
use Happyr\LocationBundle\Entity\Component;
use HappyR\SlugifyBundle\Services\SlugifyService;

/**
 * All the other manager extends this class but the are not accessed by the service container.
 * This class does all the work.
 *
 * @author tobias
 */
class LocationManager
{
    /**
     * @var \Doctrine\ORM\EntityManager em
     */
    protected $em;

    /**
     * @var \HappyR\SlugifyBundle\Services\SlugifyService slugifier
     */
    protected $slugifier;

    /**
     * @var string typePrefix
     */
    protected $typePrefix = 'HappyrLocationBundle:';

    /**
     * @param EntityManager  $em
     * @param SlugifyService $slugifier
     */
    public function __construct(EntityManager $em, SlugifyService $slugifier)
    {
        $this->em = $em;
        $this->slugifier = $slugifier;
    }

    /**
     * Returns a object of $type. This will always return a object. A new object will be created if it does not exsist.
     *
     * @param string $entity      must be safe. Don't let the user affect this one. Example "City", "Region"
     * @param string $name        The name of the type.
     * @param string $countryCode 2 digit country code, UPPERCASE
     *
     * @return mixed
     */
    public function getObject($entity, $name, $countryCode)
    {
        $this->isValidEntity($entity);

        if ($name == null) {
            return;
        }

        $entity = $this->typePrefix.$entity;
        $name = $this->beautifyName($name);

        /*
         * The slugifier removes words like "is", "at" etc.. that's why we just strlower the country codes..
         */
        if ($entity == 'HappyrLocationBundle:Country') {
            $slug = strtolower($name);
            $countryCode = null;
        } else {
            $slug = $this->slugifier->slugify($name);
        }
        $conditions = array('slug' => $slug);

        if ($countryCode !== null) {
            $conditions['country'] = $countryCode;
        }

        //fetch object
        $object = $this->em->getRepository($entity)->findOneBy($conditions);

        //if object is not found
        if (!$object) {
            // Assert: this will never be Country
            $entityName = explode(':', $entity);
            $entityNamespace = 'Happyr\LocationBundle\Entity\\'.$entityName[1];

            //create
            $object = new $entityNamespace($name, $slug, $countryCode);
        }

        return $object;
    }

    /**
     * Return an object by slug.
     *
     * @param string $entity must be safe. Don't let the user affect this one. Example "City", "Region"
     * @param string $slug
     * @param string $countryCode 2 digit country code, UPPERCASE
     *
     * @return Component|null
     */
    public function findOneObjectBySlug($entity, $slug, $countryCode)
    {
        $this->isValidEntity($entity);

        if (empty($slug)) {
            return;
        }

        $entity = $this->typePrefix.$entity;

        return $this->em->getRepository($entity)->findOneBy(array('slug' => $slug, 'country' => $countryCode));
    }

    /**
     * Return an object by name
     * This function slugifys the name and runs findOneObjectBySlug.
     *
     * @param string $entity must be safe. Don't let the user affect this one. Example "City", "Region"
     * @param string $name
     * @param string $countryCode 2 digit country code, UPPERCASE
     *
     * @return Object
     */
    public function findOneObjectByName($entity, $name, $countryCode)
    {
        $this->isValidEntity($entity);

        if (empty($name)) {
            return;
        }

        $entity = $this->typePrefix.$entity;

        return $this->em->getRepository($entity)->findOneBy(array('name' => $name, 'country' => $countryCode));
    }

    /**
     * Special case to handle Swedish Kommun that sometimes are post-fixed with "s? Kommun" and some times not
     *
     * @param string $name
     * @param string $countryCode 2 digit country code, UPPERCASE
     *
     * @return Object|void
     */
    public function findOneMunicipalityByName($name, $countryCode)
    {
        if (empty($name)) {
            return;
        }

        if (!preg_match('|.*?s? Kommun|sim', $name)) {
            return $this->findOneObjectByName('Municipality', $name, $countryCode);
        }

        //If it ends if "* Kommun"
        if (null === $mun = $this->findOneObjectByName('Municipality', substr($name, 0, -7), $countryCode)) {
            $mun = $this->findOneObjectByName('Municipality', substr($name, 0, -8), $countryCode);
        }

        return $mun;
    }

    /**
     * Rename a object.
     *
     * @param string    $entity    must be safe. Don't let the user affect this one. Example "City", "Region"
     * @param Component $component
     * @param String    $name
     */
    public function renameObject($entity, Component $component, $name)
    {
        $name = $this->beautifyName($name);
        $component->setName($name);
        $component->setSlug($this->slugifier->slugify($name));

        $this->em->persist($component);
        $this->em->flush();
    }

    /**
     * Remove a Location object.
     *
     * @param Component $component
     */
    public function removeObject(Component $component)
    {
        $this->em->remove($component);
        $this->em->flush();
    }

    /**
     * Merge two Location Objects into one.
     * This will move all Location's references from $copy to $org
     * This will remove the copy Object.
     *
     * @param String    $databaseName The $databaseName will be inserted in a query. Must be safe
     * @param Component $org
     * @param Component $copy
     */
    public function mergeObjects($databaseName, Component $org, Component $copy)
    {
        $this->em->createQuery(
            'UPDATE EastitLegoLocationBundle:Location e SET e.'
            .$databaseName.'=:org_id WHERE e.'.$databaseName.'=:copy_id'
        )
            ->setParameter('org_id', $org->getId())
            ->setParameter('copy_id', $copy->getId())
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
        return mb_convert_case(mb_strtolower(trim($name), 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
    }

    /**
     * @param $entity
     */
    protected function isValidEntity($entity)
    {
        $validEntities = array('City', 'Country', 'Municipality', 'Region');
        if (!in_array($entity, $validEntities)) {
            throw new \InvalidArgumentException(
                sprintf(
                    '%s is not a valid entity to use with the LocationManager. You should use of of these: %s',
                    $entity,
                    implode(', ', $validEntities)
                )
            );
        }
    }
}
