<?php

declare (strict_types=1);
namespace MonorepoHelper;

use PharIo\Version\Version;
use Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface;
use Symplify\MonorepoBuilder\Release\Process\ProcessRunner;
use Symplify\MonorepoBuilder\ValueObject\Option;
use MonorepoBuilder202208\Symplify\PackageBuilder\Parameter\ParameterProvider;
use Throwable;
class TagVersionReleaseWorker implements ReleaseWorkerInterface
{
    use VersionHelper;
    /**
     * @var string
     */
    private $branchName;
    /**
     * @var \Symplify\MonorepoBuilder\Release\Process\ProcessRunner
     */
    private $processRunner;

    private $branchVersionFormat;

    /** @var ParameterProvider */
    private $parameterProvider;

    public function __construct(ProcessRunner $processRunner, ParameterProvider $parameterProvider)
    {
        $this->processRunner = $processRunner;
        $this->branchName = $parameterProvider->provideStringParameter(Option::DEFAULT_BRANCH_NAME);
        $this->parameterProvider = $parameterProvider;
    }
    public function work(Version $version) : void
    {
        try {
            $gitAddCommitCommand = \sprintf('git add . && git commit -m "prepare release" && git push origin "%s"', $this->getVersionDev($version));
            $this->processRunner->run($gitAddCommitCommand);
        } catch (Throwable $exception) {
            // nothing to commit
        }
        $this->processRunner->run('git tag ' . $version->getOriginalString());
        $this->processRunner->run('git push --tags');
    }
    public function getDescription(Version $version) : string
    {
        return \sprintf('Add local/remote tag "%s"', $version->getOriginalString());
    }
}
