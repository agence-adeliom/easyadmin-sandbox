<?php

namespace MonorepoHelper;

use PharIo\Version\Version;

trait VersionHelper
{
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
