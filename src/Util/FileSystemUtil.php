<?php
declare(strict_types=1);

namespace Raxos\Foundation\Util;

use Raxos\Foundation\Error\TemporaryFileFailedException;

/**
 * Class FileSystemUtil
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Util
 * @since 2.0.0
 */
final class FileSystemUtil
{

    /**
     * Returns a new temporary file path.
     *
     * @return string
     * @throws TemporaryFileFailedException
     * @author Bas Milius <bas@mili.us>
     * @since 2.0.0
     */
    public static function temporaryFile(): string
    {
        return tempnam(sys_get_temp_dir(), 'rx') ?: throw new TemporaryFileFailedException();
    }

    /**
     * Returns a new temporary file stream.
     *
     * @return mixed
     * @throws TemporaryFileFailedException
     * @author Bas Milius <bas@mili.us>
     * @since 2.0.0
     */
    public static function temporaryFileStream(): mixed
    {
        return tmpfile() ?: throw new TemporaryFileFailedException();
    }

}
