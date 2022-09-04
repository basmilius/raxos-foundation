<?php
declare(strict_types=1);

namespace Raxos\Foundation\Event;

use BackedEnum;
use ReflectionClass;
use ReflectionMethod;
use StringBackedEnum;

/**
 * Trait Emitter
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Event
 * @since 1.0.0
 */
trait Emitter
{

    private static array $listeners;

    /**
     * Adds the given listener class.
     *
     * @param object $obj
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function listen(object $obj): void
    {
        $class = new ReflectionClass($obj);
        $methods = $class->getMethods(ReflectionMethod::IS_PUBLIC);

        foreach ($methods as $method) {
            $attributes = $method->getAttributes(On::class);

            /** @var On $on */
            $on = $attributes[0]->newInstance();

            self::$listeners[$on->getEventName()][] = [$obj, $method->getName()];
        }
    }

    /**
     * Emits an event with the given arguments.
     *
     * @param StringBackedEnum|string $eventName
     * @param mixed ...$arguments
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    protected final function emit(BackedEnum|string $eventName, mixed ...$arguments): void
    {
        if ($eventName instanceof StringBackedEnum) {
            $eventName = $eventName->value;
        }

        $listeners = self::$listeners[$eventName] ?? [];

        foreach ($listeners as $listener) {
            call_user_func($listener, $this, ...$arguments);
        }
    }

}
