<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
//    $rectorConfig->paths([
//        __DIR__.'/src',
//        __DIR__.'/lib/easy-admin-user-bundle/src',
//        __DIR__.'/lib/easy-block-bundle/src',
//        __DIR__.'/lib/easy-blog-bundle/src',
//        __DIR__.'/lib/easy-common-bundle/src',
//        __DIR__.'/lib/easy-config-bundle/src',
//        __DIR__.'/lib/easy-editor-bundle/src',
//        __DIR__.'/lib/easy-faq-bundle/src',
//        __DIR__.'/lib/easy-fields-bundle/src',
//        __DIR__.'/lib/easy-media-bundle/src',
//        __DIR__.'/lib/easy-menu-bundle/src',
//        __DIR__.'/lib/easy-page-bundle/src',
//        __DIR__.'/lib/easy-redirect-bundle/src',
//        __DIR__.'/lib/easy-seo-bundle/src',
//    ]);

    $rectorConfig->paths([
        __DIR__.'/_recipes/agence-adeliom/easy-admin-user-bundle/2.0',
        __DIR__.'/_recipes/agence-adeliom/easy-block-bundle/2.0',
        __DIR__.'/_recipes/agence-adeliom/easy-blog-bundle/2.0',
        //__DIR__.'/_recipes/agence-adeliom/easy-common-bundle/2.0',
        __DIR__.'/_recipes/agence-adeliom/easy-config-bundle/2.0',
        __DIR__.'/_recipes/agence-adeliom/easy-editor-bundle/2.0',
        __DIR__.'/_recipes/agence-adeliom/easy-faq-bundle/2.0',
        //__DIR__.'/_recipes/agence-adeliom/easy-fields-bundle/2.0',
        __DIR__.'/_recipes/agence-adeliom/easy-media-bundle/2.0',
        __DIR__.'/_recipes/agence-adeliom/easy-menu-bundle/2.0',
        __DIR__.'/_recipes/agence-adeliom/easy-page-bundle/2.0',
        __DIR__.'/_recipes/agence-adeliom/easy-redirect-bundle/2.0',
        //__DIR__.'/_recipes/agence-adeliom/easy-seo-bundle/2.0',
    ]);

    // register a single rule
    $rectorConfig->rule(InlineConstructorDefaultToPropertyRector::class);

    $rectorConfig->sets([
        \Rector\Doctrine\Set\DoctrineSetList::GEDMO_ANNOTATIONS_TO_ATTRIBUTES,
        \Rector\Doctrine\Set\DoctrineSetList::ANNOTATIONS_TO_ATTRIBUTES,
        \Rector\Doctrine\Set\DoctrineSetList::DOCTRINE_CODE_QUALITY,
        \Rector\Symfony\Set\SymfonySetList::ANNOTATIONS_TO_ATTRIBUTES,
        \Rector\Symfony\Set\SymfonySetList::SYMFONY_CODE_QUALITY,
        \Rector\Symfony\Set\SymfonySetList::SYMFONY_60,
        \Rector\Symfony\Set\SensiolabsSetList::FRAMEWORK_EXTRA_61,
        \Rector\Set\ValueObject\LevelSetList::UP_TO_PHP_80,
        \Rector\Set\ValueObject\SetList::CODE_QUALITY,
        \Rector\Set\ValueObject\SetList::CODING_STYLE,
    ]);

//    $rectorConfig->sets([
//        \Rector\Set\ValueObject\DowngradeLevelSetList::DOWN_TO_PHP_80,
//        \Rector\Set\ValueObject\DowngradeSetList::PHP_80,
//    ]);
};
