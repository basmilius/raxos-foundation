<?php
declare(strict_types=1);

namespace Raxos\Foundation\Collection;

use ArrayAccess;
use function is_array;
use function is_int;
use function is_string;
use function sprintf;

/**
 * Class Take
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Collection
 * @since 1.0.0
 */
final class Take
{

    /**
     * Takes the given schema from the given object.
     *
     * @param array|ArrayAccess $object
     * @param array $schema
     * @param bool $isStrict
     *
     * @return array
     * @throws CollectionException
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function schema(array|ArrayAccess $object, array $schema, bool $isStrict = false): array
    {
        $result = [];

        foreach ($schema as $entry) {
            if (is_string($entry) || is_int($entry)) {
                if ($isStrict && !isset($object[$entry])) {
                    throw new CollectionException(sprintf('Key "%s" does not exist.', $entry), CollectionException::ERR_INVALID_KEY);
                }

                $result[$entry] = $object[$entry];
            } elseif (is_array($entry) && isset($entry[1])) {
                [$key, $keys] = $entry;

                if ($isStrict && !isset($object[$key])) {
                    throw new CollectionException(sprintf('Key "%s" does not exist.', $key), CollectionException::ERR_INVALID_KEY);
                }

                $result[$key] = $object[$key] === null ? null : self::schema($object[$key], $keys);
            } else {
                throw new CollectionException(sprintf('Key "%s" is invalid.', $entry), CollectionException::ERR_INVALID_KEY);
            }
        }

        return $result;
    }

}
