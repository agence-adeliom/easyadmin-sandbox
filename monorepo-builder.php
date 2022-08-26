<?php

declare(strict_types=1);

use Symplify\MonorepoBuilder\Config\MBConfig;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\AddTagToChangelogReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\PushNextDevReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\PushTagReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\SetCurrentMutualConflictsReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\SetCurrentMutualDependenciesReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\SetNextMutualDependenciesReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\TagVersionReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\UpdateBranchAliasReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\UpdateReplaceReleaseWorker;

return static function (MBConfig $mbConfig): void {
    $mbConfig->packageDirectories([__DIR__.'/lib']);

    $mbConfig->defaultBranch('main');

    $mbConfig->packageAliasFormat('<major>.<minor>.x-dev');
    $mbConfig->parameters()->set("branch_version", '<major>.x');

    $mbConfig->dataToRemove([
        'require' => [
            // remove these to merge of packages' composer.json
            'tracy/tracy' => '*',
            'phpunit/phpunit' => '*',
        ],
        'minimum-stability' => 'dev',
        'prefer-stable' => true,
    ]);

    $mbConfig->workers([
        // release workers - in order to execute
        //UpdateReplaceReleaseWorker::class,
        //SetCurrentMutualConflictsReleaseWorker::class,
        \MonorepoHelper\PushRepositoryVersion::class,
        SetCurrentMutualDependenciesReleaseWorker::class,
        AddTagToChangelogReleaseWorker::class,
        TagVersionReleaseWorker::class,
        PushTagReleaseWorker::class,
        SetNextMutualDependenciesReleaseWorker::class,
        UpdateBranchAliasReleaseWorker::class,
        PushNextDevReleaseWorker::class,
    ]);
};
