Happyr LocationBundle
=====================

A Symfony2 Bundle to handle location in Sweden, Norway and Denmark.


## Installation

1. Install with composer:

    ```
    php composer.phar require happyr/location-bundle
    ```

2. Enable the bundle:

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
    
If you want to have the address name in the same language as the country the address is located 
you should use the ExcessiveGeocoder. Ie, Russian addresses will be in Russian and not in English. 

``` yaml
happyr_location:
  enable_excessive_geocoder: true
```
