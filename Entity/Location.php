<?php

namespace HappyR\LocationBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * HappyR\LocationBundle\Entity\Location
 *
 * @ORM\Table(name="Location")
 * @ORM\Entity
 */
class Location extends BaseLocation
{
}