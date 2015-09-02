<?php

namespace Happyr\LocationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Happyr\LocationBundle\Entity\Region.
 *
 * A region is the like "State", "Län" or "Bundesland"
 *
 * @ORM\Table(name="LocationRegion", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="slug_index", columns={"slug", "country"})})
 *
 * @ORM\Entity(repositoryClass="Happyr\LocationBundle\Entity\ComponentRepository")
 */
class Region extends Component
{
}
