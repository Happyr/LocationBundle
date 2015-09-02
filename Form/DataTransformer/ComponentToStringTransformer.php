<?php

namespace Happyr\LocationBundle\Form\DataTransformer;

use Happyr\LocationBundle\Entity\Component;
use Happyr\LocationBundle\Manager\LocationManager;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * @author Tobias Nyholm
 */
class ComponentToStringTransformer implements DataTransformerInterface
{
    /**
     * @var \Happyr\LocationBundle\Manager\LocationManager lm
     */
    protected $lm;

    /**
     * @var string type
     */
    protected $type;

    /**
     * @param LocationManager $lm
     * @param string          $type
     */
    public function __construct(LocationManager $lm, $type)
    {
        $this->lm = $lm;
        $this->type = $type;
    }

    /**
     * "app data"=> "norm data".
     *
     * @param mixed $data
     *
     * @return mixed|string
     */
    public function transform($data)
    {
        if ($this->type == 'Country') {
            return $data;
        }

        if (!$data) {
            return '';
        }

        return $data->__toString();
    }

    /**
     * get the location component object.
     *
     * @param mixed $data
     *
     * @return Component
     */
    public function reverseTransform($data)
    {
        return $this->lm->getObject($this->type, $data);
    }
}
