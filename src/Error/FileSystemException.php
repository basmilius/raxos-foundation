<?php
declare(strict_types=1);

namespace Raxos\Foundation\Error;

use Raxos\Http\HttpResponseCode;

/**
 * Class FileSystemException
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Error
 * @since 2.0.0
 */
final class FileSystemException extends RaxosException
{

    /**
     * Returns the exception for when the creation of a temporary
     * file failed.
     *
     * @return self
     * @author Bas Milius <bas@mili.us>
     * @since 2.0.0
     */
    public static function temporaryFileFailed(): self
    {
        return new self(
            HttpResponseCode::INTERNAL_SERVER_ERROR,
            'file_system_error',
            'Failed to create a temporary file.'
        );
    }

}
