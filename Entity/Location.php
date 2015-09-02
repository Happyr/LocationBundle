<?php

namespace Happyr\LocationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Happyr\LocationBundle\Entity\Location.
 *
 * This is the wrapper object that has all the relations with the components
 *
 * @ORM\Table(name="Location")
 * @ORM\Entity
 */
class Location extends BaseLocation
{
}
