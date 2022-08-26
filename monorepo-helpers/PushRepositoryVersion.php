<?php

declare (strict_types=1);
namespace MonorepoHelper;

use MonorepoBuilder202208\Symplify\PackageBuilder\Parameter\ParameterProvider;
use PharIo\Version\Version;
use Symplify\MonorepoBuilder\DevMasterAliasUpdater;
use Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider;
use Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface;
use Symplify\MonorepoBuilder\Release\Process\ProcessRunner;
use Symplify\MonorepoBuilder\Utils\VersionUtils;
use Symplify\MonorepoBuilder\ValueObject\Option;

final class PushRepositoryVersion implements ReleaseWorkerInterface
{
    /**
     * @var string
     */
    private $branchName;
    /**
     * @var \Symplify\MonorepoBuilder\Release\Process\ProcessRunner
     */
    private $processRunner;
    /**
     * @var \Symplify\MonorepoBuilder\Utils\VersionUtils
     */
    private $versionUtils;

    private $branchVersionFormat;

    /** @var ParameterProvider */
    private $parameterProvider;

    public function __construct(ProcessRunner $processRunner, VersionUtils $versionUtils, ParameterProvider $parameterProvider)
    {
        $this->processRunner = $processRunner;
        $this->versionUtils = $versionUtils;
        $this->branchName = $parameterProvider->provideStringParameter(Option::DEFAULT_BRANCH_NAME);
        $this->branchVersionFormat = $parameterProvider->provideStringParameter('branch_version');

    }

    public function work(Version $version) : void
    {
        $versionInString = $this->getVersionDev($version);
        $gitAddCommitCommand = \sprintf('git switch -c "%s" && git push –set-upstream origin "%s" && git switch -c "%s"', $versionInString, $versionInString, $this->branchName);
        $this->processRunner->run($gitAddCommitCommand);
        $this->parameterProvider->changeParameter(Option::DEFAULT_BRANCH_NAME, $versionInString);
    }
    public function getDescription(Version $version) : string
    {
        $versionInString = $this->getVersionDev($version);
        return \sprintf('Create/Update "%s" branch on remote repository', $versionInString);
    }
    private function getVersionDev(Version $version) : string
    {
        return $this->getAliasFormat($version);
    }

    public function getAliasFormat($version) : string
    {
        $version = $this->normalizeVersion($version);
        /** @var Version $minor */
        $minor = $this->getMinorNumber($version);
        return \str_replace(['<major>', '<minor>'], [$version->getMajor()->getValue(), $minor], $this->branchVersionFormat);
    }

    /**
     * @param \PharIo\Version\Version|string $version
     */
    private function normalizeVersion($version) : Version
    {
        if (\is_string($version)) {
            return new Version($version);
        }
        return $version;
    }
    private function getMinorNumber(Version $version) : int
    {
        if ($version->hasPreReleaseSuffix()) {
            return (int) $version->getMinor()->getValue();
        }
        return $version->getMinor()->getValue();
    }
}
