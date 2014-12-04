<?php

namespace Happyr\LocationBundle;

use Happyr\LocationBundle\DependencyInjection\Compiler\GeocoderPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class HappyRLocationBundle
 *
 * @author Tobias Nyholm
 *
 *
 */
class HappyrLocationBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new GeocoderPass());
    }
}
