<?php

namespace Happyr\LocationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Happyr\LocationBundle\Entity\City.
 *
 * A plain city...
 *
 * @ORM\Table(name="LocationCity", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="city_slug_index", columns={"slug", "country"})})
 * @ORM\Entity(repositoryClass="Happyr\LocationBundle\Entity\ComponentRepository")
 */
class City extends Component
{
}
