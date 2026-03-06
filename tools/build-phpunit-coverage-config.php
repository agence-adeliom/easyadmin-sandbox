<?php

declare(strict_types=1);

if ($argc !== 3) {
    fwrite(STDERR, "Usage: php tools/build-phpunit-coverage-config.php <source-dir> <output-file>\n");
    exit(1);
}

$sourceDir = $argv[1];
$outputFile = $argv[2];
$projectRoot = dirname(__DIR__);
$projectRootRealPath = realpath($projectRoot);
$template = $projectRoot.'/phpunit.xml.dist';

if ($projectRootRealPath === false || !is_file($template)) {
    fwrite(STDERR, "Template not found: {$template}\n");
    exit(1);
}

$sourcePath = $projectRoot.'/'.$sourceDir;
if (!is_dir($sourcePath)) {
    fwrite(STDERR, "Source directory not found: {$sourceDir}\n");
    exit(1);
}

$dom = new DOMDocument('1.0', 'UTF-8');
$dom->preserveWhiteSpace = false;
$dom->formatOutput = true;

if (!$dom->load($template)) {
    fwrite(STDERR, "Unable to load {$template}\n");
    exit(1);
}

$phpunit = $dom->documentElement;
if ($phpunit->hasAttribute('bootstrap')) {
    $bootstrap = $phpunit->getAttribute('bootstrap');
    if ($bootstrap !== '' && $bootstrap[0] !== '/') {
        $phpunit->setAttribute('bootstrap', $projectRootRealPath.'/'.$bootstrap);
    }
}

foreach ($dom->getElementsByTagName('directory') as $directoryNode) {
    $path = trim($directoryNode->textContent);
    if ($path === '' || $path[0] === '/') {
        continue;
    }

    while ($directoryNode->firstChild !== null) {
        $directoryNode->removeChild($directoryNode->firstChild);
    }

    $directoryNode->appendChild($dom->createTextNode($projectRootRealPath.'/'.$path));
}

$coverageNodes = $dom->getElementsByTagName('coverage');
if ($coverageNodes->count() === 0) {
    fwrite(STDERR, "No <coverage> node found in {$template}\n");
    exit(1);
}

$coverage = $coverageNodes->item(0);
$includeNodes = $coverage->getElementsByTagName('include');
if ($includeNodes->count() === 0) {
    $include = $dom->createElement('include');
    $coverage->appendChild($include);
} else {
    $include = $includeNodes->item(0);
    while ($include->firstChild !== null) {
        $include->removeChild($include->firstChild);
    }
}

$directory = $dom->createElement('directory', $projectRootRealPath.'/'.$sourceDir);
$directory->setAttribute('suffix', '.php');
$include->appendChild($directory);

$outputDir = dirname($projectRoot.'/'.$outputFile);
if (!is_dir($outputDir) && !mkdir($outputDir, 0777, true) && !is_dir($outputDir)) {
    fwrite(STDERR, "Unable to create directory: {$outputDir}\n");
    exit(1);
}

if ($dom->save($projectRoot.'/'.$outputFile) === false) {
    fwrite(STDERR, "Unable to write {$outputFile}\n");
    exit(1);
}
