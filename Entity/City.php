<?php

namespace HappyR\LocationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HappyR\LocationBundle\Entity\City
 *
 * @ORM\Table(name="LocationCity")
 * @ORM\Entity(repositoryClass="HappyR\LocationBundle\Entity\ComponentRepository")
 */
class City extends LocationObject
{
}
