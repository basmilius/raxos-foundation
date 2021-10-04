<?php
declare(strict_types=1);

namespace Raxos\Foundation\Util;

use ReflectionFunctionAbstract;
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
     * @author Bas Milius <bas@glybe.nl>
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

            if ($type->allowsNull()) {
                $types[] = 'null';
            }

            return $types;
        }

        return null;
    }

}
