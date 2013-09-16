<?php
namespace HappyR\LocationBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Acme\TaskBundle\Entity\country;

class CountryTransformer implements DataTransformerInterface
{
    /**
     * Transforms an object (country) to a string (code).
     *
     * @param  country|null $country
     * @return string
     */
    public function transform($country)
    {
        if (null == $country) {
            return "";
        }

        return $country->getCode();
    }

    /**
     * We dont need any reverse transform
     */
    public function reverseTransform($code)
    {
        return $code;
    }
}
