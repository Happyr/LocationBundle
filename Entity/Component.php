<?php

namespace Happyr\LocationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Component. This is a component of the location object.
 *
 * @author Tobias Nyholm
 *
 * @ORM\MappedSuperclass
 */
abstract class Component
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=64)
     *
     * @Assert\Length(max=64)
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=64, nullable=true)
     *
     * @Assert\Length(max=64)
     */
    protected $slug;

    /**
     * This field is just a way of separating the unique filter.
     *
     * @var string country
     *
     * @ORM\Column(type="string", length=10)
     */
    protected $country;

    /**
     * @param string $name
     * @param string $slug
     * @param string $country
     */
    public function __construct($name, $slug, $country)
    {
        $this->name = $name;
        $this->slug = $slug;
        $this->setCountry($country);
    }

    /**
     * A to string method.
     *
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
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
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
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
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $country
     *
     * @return $this
     */
    public function setCountry($country)
    {
        $this->country = strtoupper($country);

        return $this;
    }
}
