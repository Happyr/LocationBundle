<?php

namespace Happyr\LocationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Happyr\LocationBundle\Entity\Region
 *
 * This is what we in Sweden call "postort"
 *
 * @ORM\Table(name="LocationRegion")
 * @ORM\Entity(repositoryClass="Happyr\LocationBundle\Entity\ComponentRepository")
 */
class Region extends Component
{
}
