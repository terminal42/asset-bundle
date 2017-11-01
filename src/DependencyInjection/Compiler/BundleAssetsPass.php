<?php

namespace Terminal42\AssetBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class BundleAssetsPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('assets.packages')) {
            return;
        }

        $packages = $container->getDefinition('assets.packages');

        if ($container->hasDefinition('assets._version_default')) {
            $version = new Reference('assets._version_default');
        } else {
            $version = new Reference('assets.empty_version_strategy');
        }

        /** @var Bundle $bundle */
        foreach ($container->get('kernel')->getBundles() as $bundle) {
            if (!is_dir($originDir = $bundle->getPath().'/Resources/public')) {
                continue;
            }

            if ($extension = $bundle->getContainerExtension()) {
                $packageName = $extension->getAlias();
            } else {
                $packageName = $this->getPackageName($bundle);
            }

            $serviceId = 'assets._package_'.$packageName;
            $basePath = 'bundles/' . $bundle->getName();
            $container->setDefinition($serviceId, $this->createPackageDefinition($basePath, $version));

            $packages->addMethodCall('addPackage', [$packageName, new Reference($serviceId)]);
        }
    }

    /**
     * Creates a definition for an asset package.
     *
     * @param string    $basePath
     * @param Reference $version
     *
     * @return ChildDefinition
     */
    private function createPackageDefinition(string $basePath, Reference $version): ChildDefinition
    {
        $package = new ChildDefinition('assets.path_package');
        $package
            ->setPublic(false)
            ->replaceArgument(0, $basePath)
            ->replaceArgument(1, $version)
        ;

        return $package;
    }

    /**
     * Gets a package name from bundle name, trying to emulate what a bundle extension would look like.
     *
     * @param Bundle $bundle
     *
     * @return string
     */
    private function getPackageName(Bundle $bundle): string
    {
        $className = get_class($this);

        if ('Bundle' !== substr($className, -6)) {
            return $bundle->getName();
        }

        $classBaseName = substr(strrchr($className, '\\'), 1, -6);

        return Container::underscore($classBaseName);
    }
}
