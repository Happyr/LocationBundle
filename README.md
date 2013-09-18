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
            new HappyR\SlugifyBundle\HappyRSlugifyBundle(),
        );
    }
    ```

### Optional installation

If you want geocoding, install the
[HappyR GoobleMaps GeocoderBundle](https://github.com/HappyR/GoogleMapsGeocoderBundle) and enable it with the
location plugin like this:

```yaml
#/app/config/config.yml
happy_r_location:
  geocoder_service: 'happyr.geocoder'

```