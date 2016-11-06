<?php

namespace Happyr\LocationBundle\Manager;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Happyr\LocationBundle\Entity\Component;

/**
 * All the other manager extends this class but the are not accessed by the service container.
 * This class does all the work.
 *
 * @author tobias
 */
class LocationManager
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var string typePrefix
     */
    protected $typePrefix = 'HappyrLocationBundle:';

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
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
    public function getObject($entity, $name, $countryCode, $options = array())
    {
        $this->isValidEntity($entity);

        if ($name == null) {
            return;
        }

        $entity = $this->typePrefix.$entity;
        $name = $this->beautifyName($name);

        if ($entity == 'HappyrLocationBundle:Country') {
            $name = strtoupper($name);
            $countryCode = null;
            $conditions = array('slug' => $name);
        } else {
            $conditions = $this->prepareConditions($countryCode, $options, $name);
        }

        //fetch object
        $object = $this->em->getRepository($entity)->findOneBy($conditions);

        //if object is not found
        if (!$object) {
            // Assert: this will never be Country
            $entityName = explode(':', $entity);
            $entityNamespace = 'Happyr\LocationBundle\Entity\\'.$entityName[1];

            $slug = $this->slugify($name);

            //create
            $object = new $entityNamespace($name, $slug, $countryCode);
            if (isset($options['conditions'])) {
                foreach ($options['conditions'] as $cName => $cValue) {
                    $setter = 'set'.ucfirst($cName);
                    $object->$setter($cValue);
                }
            }
        }

        return $object;
    }

    /**
     * Return an object by slug.
     *
     * @param string $entity      must be safe. Don't let the user affect this one. Example "City", "Region"
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
     * Return an object by name.
     *
     * @param string $entity      must be safe. Don't let the user affect this one. Example "City", "Region"
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

        if ($entity == 'Country') {
            $conditions = array('slug' => $name);
        } else {
            $conditions = array('name' => $name, 'country' => $countryCode);
        }

        $entity = $this->typePrefix.$entity;

        return $this->em->getRepository($entity)->findOneBy($conditions);
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

    /**
     * @param $countryCode
     * @param $options
     * @param $name
     *
     * @return array
     */
    private function prepareConditions($countryCode, $options, $name)
    {
        $conditions = array('name' => $name,);

        if ($countryCode !== null) {
            $conditions['country'] = $countryCode;
        }

        if (isset($options['conditions'])) {
            $conditions = array_merge($conditions, $options['conditions']);
        }

        return $conditions;
    }

    /**
     * @param string $string
     *
     * @return string
     */
    protected function slugify($string)
    {
        $slugify = new Slugify();

        return $slugify->slugify($string);
    }
}
