<?php
namespace Happyr\LocationBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Acme\TaskBundle\Entity\country;

/**
 * Class CountryTransformer
 *
 * @author Tobias Nyholm
 *
 *
 */
class CountryTransformer implements DataTransformerInterface
{
    /**
     * Transforms an object (country) to a string (code).
     *
     *
     * @param mixed $country
     *
     * @return mixed|string
     */
    public function transform($country)
    {
        if (null == $country) {
            return "";
        }

        return $country->getCode();
    }

    /**
     * We don't need any reverse transform
     *
     * @param mixed $code
     *
     * @return mixed
     */
    public function reverseTransform($code)
    {
        return $code;
    }
}
