<?php
declare(strict_types=1);

namespace Raxos\Foundation\Util;

use ReflectionNamedType;
use ReflectionType;
use ReflectionUnionType;

/**
 * Class ReflectionUtil
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Util
 * @since 1.0.0
 */
final class ReflectionUtil
{

    /**
     * Gets all the types as an array.
     *
     * @param ReflectionType $type
     *
     * @return string[]|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function getTypes(ReflectionType $type): ?array
    {
        $types = [];

        if ($type instanceof ReflectionNamedType) {
            $types[] = $type->getName();

            if ($type->allowsNull()) {
                $types[] = 'null';
            }

            return $types;
        }

        if ($type instanceof ReflectionUnionType) {
            foreach ($type->getTypes() as $t) {
                $types[] = $t->getName();
            }

            return $types;
        }

        return null;
    }

}
