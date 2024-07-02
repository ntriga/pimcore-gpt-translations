<?php

namespace Ntriga\PimcoreGPTTranslations;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class PimcoreGPTTranslationsBundle extends Bundle
{
     public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../config'));
        $loader->load('services.yaml');
    }
}