<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/src',
        __DIR__ . '/lib/EasyAdminUserBundle/src',
        __DIR__ . '/lib/EasyBlockBundle/src',
        __DIR__ . '/lib/EasyBlogBundle/src',
        __DIR__ . '/lib/EasyCommonBundle/src',
        __DIR__ . '/lib/EasyConfigBundle/src',
        __DIR__ . '/lib/EasyEditorBundle/src',
        __DIR__ . '/lib/EasyFaqBundle/src',
        __DIR__ . '/lib/EasyFieldsBundle/src',
        __DIR__ . '/lib/EasyMediaBundle/src',
        __DIR__ . '/lib/EasyMenuBundle/src',
        __DIR__ . '/lib/EasyPageBundle/src',
        __DIR__ . '/lib/EasyRedirectBundle/src',
        __DIR__ . '/lib/EasySeoBundle/src'
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
        \Rector\Set\ValueObject\SetList::CODING_STYLE
    ]);

//    $rectorConfig->sets([
//        \Rector\Set\ValueObject\DowngradeLevelSetList::DOWN_TO_PHP_80,
//        \Rector\Set\ValueObject\DowngradeSetList::PHP_80,
//    ]);
};
