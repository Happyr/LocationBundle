Happyr LocationBundle
=====================

A Symfony2 Bundle to handle locations. This provided a Locaiton object with differnet parts to clearly identify a location.



## Installation

### 1. Install with composer:

```
php composer.phar require happyr/location-bundle
```

### 2. Enable the bundle:

```php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Happyr\LocationBundle\HappyrLocationBundle(),
    );
}
```

### 3. Add a geocoder

You need to specify a geocoder service to in the coniguration. The geoder must inplement the [GeocoderInterface](/Geocoder/GeocoderInterface.php)

``` yaml
happyr_location:
    geocoder_service: 'acme.geocoder'
```


## Usage

``` php 
//any form 
public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('location', 'location', array(
                    'components'=>array(
                        'country'=>true,
                        'city'=>true,
                    )
                ));
    }
```

