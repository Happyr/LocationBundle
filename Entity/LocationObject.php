<?php

namespace HappyR\LocationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eastit\Darwin\CommonBundle\Entity\AutoComplete;

/**
 * Eastit\Darwin\CommonBundle\Entity\AutoComplete
 *
 * @ORM\MappedSuperclass
 */
class LocationObject extends AutoComplete
{
}
