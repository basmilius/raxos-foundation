<?php
declare(strict_types=1);

namespace Raxos\Foundation\Event;

use ReflectionClass;
use ReflectionMethod;

/**
 * Trait Emitter
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Event
 * @since 1.0.0
 */
trait Emitter
{

    private array $listeners;

    /**
     * Adds the given listener class.
     *
     * @param object $obj
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function listen(object $obj): void
    {
        $class = new ReflectionClass($obj);
        $methods = $class->getMethods(ReflectionMethod::IS_PUBLIC);

        foreach ($methods as $method) {
            $attributes = $method->getAttributes(On::class);

            /** @var On $on */
            $on = $attributes[0]->newInstance();

            $this->listeners[$on->getEventName()][] = [$obj, $method->getName()];
        }
    }

    /**
     * Emits an event with the given arguments.
     *
     * @param string $eventName
     * @param mixed ...$arguments
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    protected final function emit(string $eventName, mixed ...$arguments): void
    {
        $listeners = $this->listeners[$eventName] ?? [];

        foreach ($listeners as $listener) {
            call_user_func($listener, $this, ...$arguments);
        }
    }

}
