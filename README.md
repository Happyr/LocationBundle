HappyR LocationBundle
=====================

A Symfony2 Bundle to handle location




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
        new Happyr\LocationBundle\HappyRLocationBundle(),
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
    
