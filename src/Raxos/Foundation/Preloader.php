<?php
declare(strict_types=1);

namespace Raxos\Foundation;

use function basename;
use function closedir;
use function get_included_files;
use function in_array;
use function is_dir;
use function opendir;
use function pathinfo;
use function readdir;
use function realpath;
use function rtrim;
use function str_starts_with;
use const PATHINFO_EXTENSION;

/**
 * Class Preloader
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation
 * @since 1.2.0
 */
class Preloader
{

    private array $ignore = [];
    private array $included = [];
    private array $loaded = [];

    /**
     * Preloader constructor.
     *
     * @param string[] $paths
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.2.0
     */
    public function __construct(
        private array $paths = []
    ) {}

    /**
     * Adds a path to ignore.
     *
     * @param string $path
     *
     * @return void
     * @author Bas Milius <bas@mili.us>
     * @since 1.2.0
     */
    public function ignore(string $path): void
    {
        $this->ignore[] = $path;
    }

    /**
     * Adds a path to preload.
     *
     * @param string $path
     *
     * @return void
     * @author Bas Milius <bas@mili.us>
     * @since 1.2.0
     */
    public function path(string $path): void
    {
        $this->paths[] = $path;
    }

    /**
     * Preload.
     *
     * @return void
     * @author Bas Milius <bas@mili.us>
     * @since 1.2.0
     */
    public function preload(): void
    {
        $this->loaded = get_included_files();

        foreach ($this->paths as $path) {
            $path = rtrim($path, '/');
            $this->preloadPath($path);
        }
    }

    /**
     * Preloads a path.
     *
     * @param string $path
     *
     * @return void
     * @author Bas Milius <bas@mili.us>
     * @since 1.2.0
     */
    private function preloadPath(string $path): void
    {
        if (is_dir($path)) {
            $this->preloadDirectory($path);
        } else {
            $this->preloadFile($path);
        }
    }

    /**
     * Preloads a directory.
     *
     * @param string $path
     *
     * @return void
     * @author Bas Milius <bas@mili.us>
     * @since 1.2.0
     */
    private function preloadDirectory(string $path): void
    {
        $handle = opendir($path);

        while ($file = readdir($handle)) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $this->preloadPath("{$path}/{$file}");
        }

        closedir($handle);
    }

    /**
     * Preloads a file.
     *
     * @param string $path
     *
     * @return void
     * @author Bas Milius <bas@mili.us>
     * @since 1.2.0
     */
    private function preloadFile(string $path): void
    {
        if ($this->ignored($path)) {
            return;
        }

        $path = realpath($path);

        if (!$path || in_array($path, $this->included) || in_array($path, $this->loaded)) {
            return;
        }

        require $path;

        $this->loaded = get_included_files();
        $this->included[] = $path;
    }

    /**
     * Returns TRUE if the path should be ignored.
     *
     * @param string $path
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.2.0
     */
    private function ignored(string $path): bool
    {
        $basename = basename($path);

        if (str_starts_with($basename, '.')) {
            return true;
        }

        $extension = pathinfo($path, PATHINFO_EXTENSION);

        if ($extension !== 'php' || str_ends_with($path, '.html.php')) {
            return true;
        }

        foreach ($this->ignore as $ignore) {
            if (str_starts_with($path, $ignore)) {
                return true;
            }
        }

        return false;
    }

}
