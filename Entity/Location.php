<?php

namespace Happyr\LocationBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Happyr\LocationBundle\Entity\Location
 *
 * @ORM\Table(name="Location")
 * @ORM\Entity
 */
class Location extends BaseLocation
{
}