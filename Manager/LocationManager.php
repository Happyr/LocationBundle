<?php

namespace HappyR\LocationBundle\Manager;

use HappyR\LocationBundle\Entity\LocationObject;
use HappyR\LocationBundle\Entity\Location;
use Symfony\Component\Form\FormError;

/**
 * A manager to handle the general Location object
 *
 * All the other manager extends the LocationObjectManager class but the are not accessed by the service container.
 * This class holds the other managers an initialize them when needed.
 *
 *
 * @author tobias
 *
 */
class LocationManager
{
    private $em;
    private $slugifier;

    private $city=null;
    private $country=null;
    private $municipality=null;
    private $region=null;
    private $zipCode=null;

    public function __construct($em,$slugifier)
    {
        $this->em=$em;
        $this->slugifier=$slugifier;
    }

    /**
     * @return CityManager
     */
    public function getCityManager()
    {
        if($this->city==null)
            $this->city=new CityManager($this->em, $this->slugifier);

        return $this->city;
    }

     /**
     * @return CountryManager
     */
    public function getCountryManager()
    {
        if($this->country==null)
            $this->country=new CountryManager($this->em, $this->slugifier);

        return $this->country;
    }

     /**
     * @return MunicipalityManager
     */
    public function getMunicipalityManager()
    {
        if($this->municipality==null)
            $this->municipality=new MunicipalityManager($this->em, $this->slugifier);

        return $this->municipality;
    }

     /**
     * @return RegionManager
     */
    public function getRegionManager()
    {
        if($this->region==null)
            $this->region=new RegionManager($this->em, $this->slugifier);

        return $this->region;
    }

     /**
     * @return ZipCodeManager
     */
    public function getZipCodeManager()
    {
        if($this->zipCode==null)
            $this->zipCode=new ZipCodeManager($this->em, $this->slugifier);

        return $this->zipCode;
    }

     /**
     * Make sure that all the strings from form post will be an object
     *
     * @param  Location $location
     * @return Location with objects as references
     */
    /*public function handleFormPost(Location &$location, $form){
        $allSuccess=true;

        $this->handleObjectPost($location, $form, 'City', $allSuccess);
        $this->handleObjectPost($location, $form, 'Country', $allSuccess);
        $this->handleObjectPost($location, $form, 'Municipality', $allSuccess);
        $this->handleObjectPost($location, $form, 'Region', $allSuccess);
        $this->handleObjectPost($location, $form, 'ZipCode', $allSuccess);

        return $allSuccess;
    }
    */
    /**
     * handle post for one object
     */
    /*protected function handleObjectPost(Location &$location, $form, $type, &$allSuccess){
        $getObject='get'.$type;
        $setObject='set'.$type;
        $getManager=$getObject.'Manager';

        $object=$location->$getObject();
        if ($object!=null && !($object instanceof LocationObject)) { //if string
            //convert to a real object
            $object=$this->$getManager()->$getObject($object);

            if ($object==null) {//no object found and we are not allowed to create
                $allSuccess=false; $errorAdded=false;
                $lowerType=strtolower($type);
                $formError=new FormError('location.form.error.'.$lowerType);

                if ($form->has($lowerType)) {
                    //add it to the correct type
                    $form->get($lowerType)->addError($formError);
                } elseif ($form->has('location')) {
                    //check if there is a child called location
                    $child=$form->get('location');
                    if ($child->has($lowerType)) {
                        $child->get($lowerType)->addError($formError);
                    }
                } else {
                    //add it to the form
                    $form->addError($formError);
                }

            } else {//if we really got an object
                $location->$setObject($object);//set the object
            }
        }
    }
    */

}
