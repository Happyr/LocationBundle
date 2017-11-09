<?php

namespace Happyr\LocationBundle\Form\Type;

use Happyr\LocationBundle\Entity\Location;
use Happyr\LocationBundle\Form\DataTransformer\CountryTransformer;
use Happyr\LocationBundle\Form\DataTransformer\ComponentToStringTransformer;
use Happyr\LocationBundle\Form\Events\GeocodeLocationString;
use Happyr\LocationBundle\Geocoder\GeocoderInterface;
use Happyr\LocationBundle\Manager\LocationManager;
use Happyr\LocationBundle\Service\LocationService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Tobias Nyholm
 */
class LocationType extends AbstractType
{
    /**
     * @var \Happyr\LocationBundle\Manager\LocationManager
     */
    protected $lm;

    /**
     * @var LocationService locationService
     */
    protected $locationService;

    /**
     * @var GeocoderInterface
     */
    protected $geocoder;

    /**
     * @param LocationManager   $lm
     * @param GeocoderInterface $geocoder
     * @param LocationService   $ls
     */
    public function __construct(LocationManager $lm, GeocoderInterface $geocoder, LocationService $ls)
    {
        $this->lm = $lm;
        $this->locationService = $ls;
        $this->geocoder = $geocoder;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->mergeActiveParts($options);
        $this->addDefaultAttributes($options);

        $this->addActiveComponents($builder, $options);
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options.
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Happyr\LocationBundle\Entity\Location',
                'error_bubbling' => true,
                'components' => array(),
                'geocodeLocationString' => true,
                //use this one if you want to set an attr on a field
                'field' => array(),
            )
        );
    }

    /**
     * Set all parts to default false.
     *
     * @param array &$options
     */
    protected function mergeActiveParts(array &$options)
    {
        $validComponents = array(
            'address' => false,
            'country' => false,
            'city' => false,
            'municipality' => false,
            'region' => false,
            'zipCode' => false,
            'location' => false,
        );

        $options['components'] = array_merge($validComponents, $options['components']);
    }

    /**
     * Here is the default configuration for each field.
     *
     * @param array &$options
     */
    protected function addDefaultAttributes(array &$options)
    {
        $defaults = array(
            'all' => array(),
            'address' => array(
                'trim' => true,
                'label' => 'location.form.address',
            ),
            'country' => array(
                'preferred_choices' => array('SE'),
                'label' => 'location.form.country',
            ),
            'city' => array(
                'trim' => true,
                'label' => 'location.form.city',
                'attr' => array(
                    'class' => 'google-autocomplete',
                    'data-google-autocomplete-type' => '(cities)',
                ),
            ),
            'municipality' => array(
                'trim' => true,
                'label' => 'location.form.municipality',
            ),
            'region' => array(
                'trim' => true,
                'label' => 'location.form.region',
            ),
            'zipCode' => array(
                'trim' => true,
                'label' => 'location.form.zipCode',
            ),
            'location' => array(
                'label' => 'location.form.location',
                'attr' => array(
                    'data-placeholder' => 'location.form.location',
                    'class' => 'google-autocomplete',
                    'data-google-autocomplete-type' => 'geocode',
                ),
            ),
        );

        //merge defaults with the user options
        $options['field'] = array_replace_recursive($defaults, $options['field']);

        /*
         * Merge the default values
         */
        if (count($options['field']['all']) > 0) {
            foreach ($options['components'] as $component => $active) {
                if (!$active) {
                    continue;
                }

                $options['field'][$component] = array_replace_recursive(
                    $options['field']['all'],
                    $options['field'][$component]
                );
            }
        }
    }

    /**
     * Add all the active parts for this instance.
     *
     * @param FormBuilderInterface &$builder
     * @param array                &$options
     */
    protected function addActiveComponents(FormBuilderInterface &$builder, array &$options)
    {
        if ($options['components']['location']) {
            $this->addLocation($builder, $options);
        } else {
            $eventListener = new GeocodeLocationString($this->locationService, $this->geocoder);
            $builder->addEventListener(FormEvents::SUBMIT, array($eventListener, 'addCoordinates'));
        }

        if ($options['components']['country']) {
            $builder->add(
                $builder->create('country', CountryType::class, $options['field']['country'])
                    ->addModelTransformer(new CountryTransformer())
                    ->addModelTransformer(new ComponentToStringTransformer($this->lm, 'Country'))
            );
        }

        if ($options['components']['city']) {
            $builder->add(
                $builder->create('city', TextType::class, $options['field']['city'])
                    ->addModelTransformer(new ComponentToStringTransformer($this->lm, 'City'))
            );
        }

        if ($options['components']['municipality']) {
            $builder->add(
                $builder->create('municipality', TextType::class, $options['field']['municipality'])
                    ->addModelTransformer(new ComponentToStringTransformer($this->lm, 'Municipality'))
            );
        }

        if ($options['components']['address']) {
            $builder->add('address', TextType::class, $options['field']['address']);
        }

        if ($options['components']['zipCode']) {
            $builder->add('zipCode', TextType::class, $options['field']['zipCode']);
        }

        if ($options['components']['region']) {
            $builder->add(
                $builder->create('region', TextType::class, $options['field']['region'])
                    ->addModelTransformer(new ComponentToStringTransformer($this->lm, 'Region'))
            );
        }
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                &$options
     */
    protected function addLocation(FormBuilderInterface &$builder, array &$options)
    {
        $locationForm = $builder->create('location', TextType::class, $options['field']['location']);

        /*
         * if we should geocode the location string
         */
        if ($options['geocodeLocationString'] == true && $this->geocoder != null) {

            // Geocode the input
            $eventListener = new GeocodeLocationString($this->locationService, $this->geocoder);
            $builder->addEventListener(FormEvents::SUBMIT, array($eventListener, 'geocodeLocation'));

            //Make sure we got a valid geocode
            $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                /** @var $data Location */
                $data = $event->getData();
                $form = $event->getForm();

                if ($data === null) {
                    return;
                }

                if (!empty($data->getLocation()) && $data->getLat() == 0 && $data->getLng() == 0) {
                    $form->get('location')->addError(new FormError('happyr.location.geocode.failed'));
                }
            });
        }

        $builder->add($locationForm);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'location';
    }

    /**
     * @return null|string|\Symfony\Component\Form\FormTypeInterface
     */
    public function getParent()
    {
        return 'form';
    }
}
