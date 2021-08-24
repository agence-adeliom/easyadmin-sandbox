<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Generator;

/**
 * Generator is the base class for all generators.
 *
 */
class Mustache
{
    /**
     * Renders a single line. Looks for {{ var }}.
     *
     * @param string $string
     *
     * @return string
     */
    public static function renderString($string, array $parameters)
    {
        $replacer = static function ($match) use ($parameters) {
            return $parameters[$match[1]] ?? $match[0];
        };

        return preg_replace_callback('/{{\s*(.+?)\s*}}/', $replacer, $string);
    }

    /**
     * Renders a file by replacing the contents of $file with rendered output.
     *
     * @param string $file filename for the file to be rendered
     */
    public static function renderFile($file, array $parameters): void
    {
        file_put_contents($file, static::renderString(file_get_contents($file), $parameters));
    }

    /**
     * Renders a directory recursively.
     *
     * @param string $dir Path to the directory that will be recursively rendered
     */
    public static function renderDir($dir, array $parameters): void
    {
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir), \RecursiveIteratorIterator::LEAVES_ONLY) as $file) {
            if ($file->isFile()) {
                static::renderFile((string) $file, $parameters);
            }
        }
    }
}
