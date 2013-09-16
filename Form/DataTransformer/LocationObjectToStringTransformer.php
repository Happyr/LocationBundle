<?php
namespace HappyR\LocationBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Acme\TaskBundle\Entity\country;

class LocationObjectToStringTransformer implements DataTransformerInterface
{

    protected $lm;
    protected $type;

    public function __construct($lm, $type)
    {
       $this->lm=$lm;
       $this->type=$type;
    }

    /**
     * "app data"=> "norm data"
     */
    public function transform($data)
    {
        if ($this->type=='Country') {
            return $data;
        }

        if(!$data)

            return '';
        return $data->__toString();
    }

    /**
     * "app data" => "norm data"
     */
    public function reverseTransform($data)
    {
        $getType='get'.$this->type;
        $getTypeManager='get'.$this->type.'Manager';

        //get the location object
        $object= $this->lm->$getTypeManager()->$getType($data);

        return $object;
    }
}
