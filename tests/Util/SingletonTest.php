<?php
declare(strict_types=1);

namespace Util;

use Raxos\Foundation\Util\Singleton;
use function it;

it('should always return the same instance.', function (): void {
    class __TestInstance {}

    $instance1 = Singleton::get(__TestInstance::class);
    $instance2 = Singleton::get(__TestInstance::class);

    expect($instance1)->toBe($instance2);
});
