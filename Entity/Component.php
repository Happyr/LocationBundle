<?php

namespace Happyr\LocationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Component. This is a component of the location object
 *
 * @author Tobias Nyholm
 *
 * @ORM\MappedSuperclass
 */
abstract class Component
{

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $name
     *
     * @ORM\Column(type="string", length=64)
     *
     * @Assert\Length(max=64)
     * @Assert\NotBlank(message="error.notblank.general")
     *
     */
    protected $name;

    /**
     * @var string $slug
     *
     * @ORM\Column(type="string", length=64, unique=true)
     *
     * @Assert\Length(max=64)
     * @Assert\NotBlank(message="error.notblank.general")
     *
     */
    protected $slug;

    /**
     * @param string $name
     * @param string $slug
     */
    public function __construct($name, $slug)
    {
        $this->name = $name;
        $this->slug = $slug;
    }

    /**
     * A to string method
     *
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     *
     * @param string $slug
     *
     * @return $this
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }
}
