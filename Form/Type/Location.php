<?php

namespace HappyR\LocationBundle\Form\Type;

use HappyR\LocationBundle\Form\DataTransformer\CountryTransformer;
use HappyR\LocationBundle\Form\DataTransformer\LocationObjectToStringTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class Location extends AbstractType
{

    protected $lm;
    protected $geocoder;

    public function __construct($lm, $geocoder=null)
    {
       $this->lm=$lm;
       $this->geocoder=$geocoder;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->mergeActiveParts($options);
        $this->mergeLabels($options);
        $this->addDefaultAttributes($options);


        if ($options['active_parts']['locationStr']) {
           $locationStrForm= $builder->create('locationStr', 'text', array(
                'attr'=>$this->mergeAttr($options['attr']['locationStr'],$options['attr']['all'],array(
                    'label'=>$options['labels']['locationStr'],
                    'data-placeholder'=>$options['labels']['locationStr'],
                    'class'=>'google-autocomplete',
                    'data-google-autocomplete-type'=>'geocode',
                    'data-help'=>isset($options['helpers']['locationStr'])?$options['helpers']['locationStr']:'location.form.help.locationStr',
                ))
           ));

           /*
            * if we should geocode the location string
            */
           if ($options['geocodeLocationString']==true && $this->geocoder!=null) {
                   $geocoder=$this->geocoder;
                   $lm=$this->lm;
                   $builder->addEventListener(FormEvents::BIND, function (FormEvent $event) use ($geocoder, $lm) {
                        $location=$event->getData();
                        $addressObject=$geocoder->geocodeAddress($location->getLocationStr(),true);
                        if(!$addressObject){
                            return;
                        }
                        //die(print_r($addressObject,true));
                        $addressParts=$addressObject[0]->address_components;
                        $streetAddress='';

                        $location->clear();

                        //parse through the address parts
                        foreach ($addressParts as $addressPart) {
                            if (in_array('street_number', $addressPart->types)) {
                                $streetAddress.=' '.$addressPart->long_name;
                            } elseif (in_array('route', $addressPart->types)) {
                                $streetAddress=$addressPart->long_name.$streetAddress;
                            } elseif (in_array('locality', $addressPart->types)) {
                                $location->setCity($lm->getCityManager()->getCity($addressPart->long_name,true));
                            } elseif (in_array('administrative_area_level_2', $addressPart->types)) {
                                if($location->getCity()==null)
                                    $location->setCity($lm->getCityManager()->getCity($addressPart->long_name,true));
                            } elseif (in_array('country', $addressPart->types)) {
                                $location->setCountry($lm->getCountryManager()->getCountry($addressPart->short_name));
                            } elseif (in_array('postal_code', $addressPart->types)) {
                                $location->setZipCode($lm->getZipCodeManager()->getZipCode($addressPart->long_name,true));
                            } elseif (in_array('postal_town', $addressPart->types)) {
                                $location->setRegion($lm->getRegionManager()->getRegion($addressPart->long_name,true));
                            }

                        }//end for each

                        $location->setCoordLong($addressObject[0]->geometry->location->lng);
                        $location->setCoordLat($addressObject[0]->geometry->location->lat);
                        $location->setLocationStr($addressObject[0]->formatted_address);
                        $location->setAddress($streetAddress);

                        $event->setData($location);

                        return $location;

                   });
           }

           $builder->add($locationStrForm);
        }

        if ($options['active_parts']['country']) {
            $builder->add(
                $builder->create('country', 'country', array(
                    'preferred_choices' => array('SE'),
                    'attr'=>$this->mergeAttr($options['attr']['country'],$options['attr']['all'],array(
                        'label'=>$options['labels']['country'],
                        'data-help'=>isset($options['helpers']['country'])?$options['helpers']['country']:'location.form.help.country',
                       ))
                ))
                ->addModelTransformer(new CountryTransformer())
                ->addModelTransformer(new LocationObjectToStringTransformer($this->lm, 'Country'))

            );
        }

         if($options['active_parts']['city'])
            $builder->add(
                $builder->create('city', 'text', array(
                    'trim'=>true,
                    'attr'=>$this->mergeAttr($options['attr']['city'],$options['attr']['all'],array(
                        'label'=>$options['labels']['city'],
                        'class'=>'google-autocomplete',
                        'data-google-autocomplete-type'=>'(cities)',
                        'data-help'=>isset($options['helpers']['city'])?$options['helpers']['city']:'location.form.help.city',

                    ))
                ))->addModelTransformer(new LocationObjectToStringTransformer($this->lm, 'City'))
            );

        if($options['active_parts']['municipality'])
            $builder->add(
                $builder->create('municipality', 'text', array(
                    'trim'=>true,
                    'attr'=>$this->mergeAttr($options['attr']['municipality'],$options['attr']['all'],array(
                        'label'=>$options['labels']['municipality'],
                        'class'=>'autocomplete',
                        'data-help'=>isset($options['helpers']['municipality'])?$options['helpers']['municipality']:'location.form.help.municipality',
                        'data-autocomplete-url'=>'_public_location_autocomplete_municipality',
                    ))
                ))->addModelTransformer(new LocationObjectToStringTransformer($this->lm, 'Municipality'))
            );




        if($options['active_parts']['address'])
            $builder->add('address', 'text', array(
                'trim'=>true,
                'attr'=>$this->mergeAttr($options['attr']['address'],$options['attr']['all'],array(
                    'label'=>$options['labels']['address'],
                    'data-help'=>isset($options['helpers']['address'])?$options['helpers']['address']:'location.form.help.address',
                ))
            ));

        if($options['active_parts']['zipCode'])
            $builder->add(
                $builder->create('zipCode', 'text', array(
                    'trim'=>true,
                    'attr'=>$this->mergeAttr($options['attr']['zipCode'],$options['attr']['all'],array(
                        'label'=>$options['labels']['zipCode'],
                        'data-help'=>isset($options['helpers']['zipCode'])?$options['helpers']['zipCode']:'location.form.help.zipCode',
                    ))
                ))->addModelTransformer(new LocationObjectToStringTransformer($this->lm, 'ZipCode'))
            );

        if($options['active_parts']['region']){
            $builder->add(
                $builder->create('region', 'text', array(
                    'trim'=>true,
                    'attr'=>$this->mergeAttr($options['attr']['region'],$options['attr']['all'],array(
                        'label'=>$options['labels']['region'],
                        'class'=>'autocomplete',
                        'data-help'=>isset($options['helpers']['region'])?$options['helpers']['region']:'location.form.help.region',
                        'data-autocomplete-url'=>'_public_location_autocomplete_region',
                    ))
                ))->addModelTransformer(new LocationObjectToStringTransformer($this->lm, 'Region'))
            );
        }

    }

    /**
     * Set the options
     *
     * @param OptionsResolverInterface $resolver
     *
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HappyR\LocationBundle\Entity\Location',
            'active_parts'=>array(),
            'labels'=>array(),
            'helpers'=>array(),
            'geocodeLocationString'=>true,
            'field_attr'=>array(), //use this one if you want to set an attr on a field
        ));
    }


    /**
     * merge defaults with the options
     *
     * @param array $options
     * @return array
     */
    protected function mergeActiveParts(array &$options){
        $options['active_parts']= array_merge(array(
            'address'=>false,
            'country'=>false,
            'city'=>false,
            'municipality'=>false,
            'region'=>false,
            'zipCode'=>false,
            'locationStr'=>false,
        ),$options['active_parts']);
    }

    /**
     * merge defaults with the options
     *
     * @param array $options
     * @return array
     */
    protected function mergeLabels(array &$options){
        $options['labels'] = array_merge(array(
            'address'=>'location.form.address',
            'country'=>'location.form.country',
            'city'=>'location.form.city',
            'municipality'=>'location.form.municipality',
            'region'=>'location.form.region',
            'zipCode'=>'location.form.zipCode',
            'locationStr'=>'location.form.locationStr',
        ),$options['labels']);
    }

    protected function addDefaultAttributes(array &$options){
        if(isset($options['field_attr'])){
            $options['attr'] = array_merge($options['attr'], $options['field_attr']);
        }
        $options['attr'] = array_merge(array(
            'all'=>array(),
            'address'=>array(),
            'country'=>array(),
            'city'=>array(),
            'municipality'=>array(),
            'region'=>array(),
            'zipCode'=>array(),
            'locationStr'=>array(),
        ),$options['attr']);
    }

    /**
     * Merge user attributes with the default ones
     *
     * @param array $user
     * @param array $default
     *
     *
     * @return array
     */
    protected function mergeAttrArray(array $user, array $default)
    {
        //remove the label
        unset($user['label']);

        //merge the class
        if(isset($user['class']) && isset($default['class'])){
            $user['class'].=' '.$default['class'];
        }

        return array_merge($default,$user);
    }

    /**
     * Merge attributes
     *
     * @param array $field
     * @param array $allFields
     * @param array $default
     *
     */
    protected function mergeAttr(array $field, array $allFields, array $default)
    {
        $arr=$this->mergeAttrArray($field, $allFields);
        return $this->mergeAttrArray($arr, $default);
    }

    public function getName()
    {
        return 'location';
    }

     public function getParent()
    {
        return 'form';
    }
}
