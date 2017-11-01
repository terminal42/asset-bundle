# terminal42/asset-bundle

This Symfony bundle automatically registers all bundles as asset
packages.


## Installation

```bash
$ composer.phar require terminal42/asset-bundle ^1.0
```


## Usage

This bundle makes use of [named packages] for Symfony assets.
The most prominent advantage is when using Twig templates.
Symfony provides [a twig function] to include assets in a template.
Using this bundle, linking to a bundle asset removes the need to know the compiled path.

Example for [SonataAdminBundle]:

<dl>
    <dt>Previously</dt>
    <dd><code>{{ asset('bundles/sonataadmin/ajax-loader.gif') }}</code></dd>
    <dt>With Terminal42AssetBundle:</dt>
    <dd><code>{{ asset('ajax-loader.gif', 'sonata_admin') }}</code></dd>
</dl>


[named packages]: http://symfony.com/doc/current/components/asset.html#named-packages
[a twig function]: http://symfony.com/doc/current/templating.html#linking-to-assets
[SonataAdminBundle]: https://github.com/sonata-project/SonataAdminBundle
