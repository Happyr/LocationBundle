<?php
namespace Happyr\LocationBundle\Form\DataTransformer;

use Happyr\LocationBundle\Manager\LocationManager;
use Symfony\Component\Form\DataTransformerInterface;
use Acme\TaskBundle\Entity\country;

/**
 * Class ComponentToStringTransformer
 *
 * @author Tobias Nyholm
 *
 *
 */
class ComponentToStringTransformer implements DataTransformerInterface
{
    /**
     * @var \Happyr\LocationBundle\Manager\LocationManager lm
     *
     *
     */
    protected $lm;

    /**
     * @var string type
     *
     *
     */
    protected $type;

    /**
     * @param LocationManager $lm
     * @param string $type
     */
    public function __construct(LocationManager $lm, $type)
    {
        $this->lm = $lm;
        $this->type = $type;
    }

    /**
     * "app data"=> "norm data"
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
     * "app data" => "norm data"
     *
     * @param mixed $data
     *
     * @return mixed
     */
    public function reverseTransform($data)
    {

        //get the location object
        $object = $this->lm->getObject($this->type, $data);

        return $object;
    }
}
