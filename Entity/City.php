<?php

namespace Happyr\LocationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Happyr\LocationBundle\Entity\City
 *
 * @ORM\Table(name="LocationCity")
 * @ORM\Entity(repositoryClass="Happyr\LocationBundle\Entity\ComponentRepository")
 */
class City extends Component
{
}
