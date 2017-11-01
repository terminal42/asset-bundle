<?php

namespace Terminal42\AssetBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Terminal42\AssetBundle\DependencyInjection\Compiler\BundleAssetsPass;

class Terminal42AssetBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new BundleAssetsPass());
    }
}
