<?php

namespace HappyR\LocationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HappyR\LocationBundle\Entity\City
 *
 * @ORM\Table(name="LocationCity")
 * @ORM\Entity(repositoryClass="HappyR\LocationBundle\Entity\LocationObjectRepository")
 */
class City extends LocationObject
{
}
