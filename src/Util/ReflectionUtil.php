<?php
declare(strict_types=1);

namespace Raxos\Foundation\Util;

use Generator;
use ReflectionFunctionAbstract;
use ReflectionIntersectionType;
use ReflectionNamedType;
use ReflectionParameter;
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
     * Gets the parameters of the given method or function reflection instance
     * as an associative array.
     *
     * @param ReflectionFunctionAbstract $ref
     *
     * @return ReflectionParameter[]
     * @author Bas Milius <bas@mili.us>
     * @since 2.0.0
     */
    public static function getParameters(ReflectionFunctionAbstract $ref): array
    {
        $parameters = [];

        foreach ($ref->getParameters() as $parameter) {
            $parameters[$parameter->getName()] = $parameter;
        }

        return $parameters;
    }

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

        foreach (self::types($type) as $type) {
            $types[] = $type;
        }

        if (empty($types)) {
            return null;
        }

        return $types;
    }

    /**
     * Returns all the types within the given type.
     *
     * @param ReflectionType $type
     *
     * @return Generator<string>
     * @author Bas Milius <bas@mili.us>
     * @since 1.7.0
     */
    public static function types(ReflectionType $type): Generator
    {
        if ($type instanceof ReflectionIntersectionType) {
            foreach ($type->getTypes() as $subType) {
                yield from self::types($subType);
            }
        } elseif ($type instanceof ReflectionNamedType) {
            yield $type->getName();
        } elseif ($type instanceof ReflectionUnionType) {
            foreach ($type->getTypes() as $subType) {
                yield from self::types($subType);
            }
        }

        if ($type->allowsNull()) {
            yield 'null';
        }
    }

}
