<?php

namespace HappyR\LocationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HappyR\LocationBundle\Entity\Region
 *
 * This is what we in Sweden call: "postort"
 *
 * @ORM\Table(name="LocationRegion")
 * @ORM\Entity(repositoryClass="HappyR\LocationBundle\Entity\LocationObjectRepository")
 */
class Region extends LocationObject
{
}
