<?php

namespace HappyR\LocationBundle\Form\Type;

use HappyR\LocationBundle\Form\DataTransformer\CountryTransformer;
use HappyR\LocationBundle\Form\DataTransformer\ComponentToStringTransformer;
use HappyR\LocationBundle\Form\Events\GeocodeLocationString;
use HappyR\LocationBundle\Manager\LocationManager;
use HappyR\LocationBundle\Services\GeocoderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class Location
 *
 * @author Tobias Nyholm
 *
 *
 */
class LocationType extends AbstractType
{

    /**
     * @var \HappyR\LocationBundle\Manager\LocationManager $lm
     *
     *
     */
    protected $lm;

    /**
     * @var GeocoderInterface $geocoder
     *
     *
     */
    protected $geocoder;

    /**
     * @param LocationManager $lm
     * @param GeocoderInterface $geocoder
     */
    public function __construct(LocationManager $lm, GeocoderInterface $geocoder)
    {
        $this->lm=$lm;
        $this->geocoder=$geocoder;
    }

    /**
     *
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->mergeActiveParts($options);
        $this->addDefaultAttributes($options);

        $this->addActiveComponents($builder, $options);
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
            'components'=>array(),
            'geocodeLocationString'=>true,

            //use this one if you want to set an attr on a field
            'field'=>array(),
        ));
    }


    /**
     * Set all parts to default false
     *
     * @param array &$options
     *
     */
    protected function mergeActiveParts(array &$options){
        $options['components']= array_merge(array(
            'address'=>false,
            'country'=>false,
            'city'=>false,
            'municipality'=>false,
            'region'=>false,
            'zipCode'=>false,
            'location'=>false,
        ),$options['components']);
    }


    /**
     * Here is the default configuration for each field
     *
     * @param array &$options
     *
     */
    protected function addDefaultAttributes(array &$options){

        $defaults = array(
            'all'=>array(),
            'address'=>array(
                'trim'=>true,
                'label'=>'location.form.address',
            ),
            'country'=>array(
                'preferred_choices' => array('SE'),
                'label'=>'location.form.country',
            ),
            'city'=>array(
                'trim'=>true,
                'label'=>'location.form.city',
                'attr'=>array(
                    'class'=>'google-autocomplete',
                    'data-google-autocomplete-type'=>'(cities)',
                ),
            ),
            'municipality'=>array(
                'trim'=>true,
                'label'=>'location.form.municipality',
                'attr'=>array(
                    'class'=>'autocomplete',
                    'data-autocomplete-url'=>'_public_location_autocomplete_municipality',
                ),
            ),
            'region'=>array(
                'trim'=>true,
                'label'=>'location.form.region',
                'attr'=>array(
                    'class'=>'autocomplete',
                    'data-autocomplete-url'=>'_public_location_autocomplete_region',
                ),
            ),
            'zipCode'=>array(
                'trim'=>true,
                'label'=>'location.form.zipCode',
            ),
            'location'=>array(
                'label'=>'location.form.location',
                'attr'=>array(
                    'data-placeholder'=>'location.form.location',
                    'class'=>'google-autocomplete',
                    'data-google-autocomplete-type'=>'geocode',
                ),
            ),
        );

        //merge defaults with the user options
        $options['field']=$options['field']+$defaults;
    }


    /**
     * Add all the active parts for this instance
     *
     * @param FormBuilderInterface &$builder
     * @param array &$options
     *
     */
    protected function addActiveComponents(FormBuilderInterface &$builder, array &$options)
    {
        if ($options['components']['location']) {
            $this->addLocation($builder, $options);
        }

        if ($options['components']['country']) {
            $builder->add(
                $builder->create('country', 'country', $options['field']['country'])
                    ->addModelTransformer(new CountryTransformer())
                    ->addModelTransformer(new ComponentToStringTransformer($this->lm, 'Country'))
            );
        }

        if($options['components']['city']){
            $builder->add(
                $builder->create('city', 'text', $options['field']['city'])
                    ->addModelTransformer(new ComponentToStringTransformer($this->lm, 'City'))
            );
        }

        if($options['components']['municipality']){
            $builder->add(
                $builder->create('municipality', 'text', $options['field']['municipality'])
                    ->addModelTransformer(new ComponentToStringTransformer($this->lm, 'Municipality'))
            );
        }

        if($options['components']['address']){
            $builder->add('address', 'text', $options['field']['address']);
        }

        if($options['components']['zipCode']){
            $builder->add(
                $builder->create('zipCode', 'text', $options['field']['zipCode'])
                    ->addModelTransformer(new ComponentToStringTransformer($this->lm, 'ZipCode'))
            );
        }

        if($options['components']['region']){
            $builder->add(
                $builder->create('region', 'text', $options['field']['zipCode'])
                    ->addModelTransformer(new ComponentToStringTransformer($this->lm, 'Region'))
            );
        }
    }

    /**
     *
     *
     * @param FormBuilderInterface &$builder
     * @param array &$options
     *
     */
    protected function addLocation(FormBuilderInterface &$builder, array &$options)
    {
        $locationForm= $builder->create('location', 'text', $options['field']['location']);

        /*
         * if we should geocode the location string
         */
        if ($options['geocodeLocationString']==true && $this->geocoder!=null) {

            $eventListener=new GeocodeLocationString($this->lm, $this->geocoder);
            $builder->addEventListener(FormEvents::BIND, array($eventListener, 'geocodeLocation'));
        }

        $builder->add($locationForm);
    }


    /**
     *
     *
     *
     * @return string
     */
    public function getName()
    {
        return 'location';
    }

    /**
     *
     *
     *
     * @return null|string|\Symfony\Component\Form\FormTypeInterface
     */
    public function getParent()
    {
        return 'form';
    }
}
