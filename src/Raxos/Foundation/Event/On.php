<?php
declare(strict_types=1);

namespace Raxos\Foundation\Event;

use Attribute;

/**
 * Class On
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Event
 * @since 1.0.0
 */
#[Attribute(Attribute::TARGET_METHOD)]
final class On
{

    /**
     * On constructor.
     *
     * @param string $eventName
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __construct(public string $eventName)
    {
    }

    /**
     * Gets the event name.
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function getEventName(): string
    {
        return $this->eventName;
    }

}
