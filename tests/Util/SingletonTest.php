<?php
declare(strict_types=1);

use Raxos\Foundation\Util\Singleton;

covers(Singleton::class);

it('creates, caches and explicitly replaces one instance per class', function (): void {
    $class = new class
    {
    }::class;
    expect(Singleton::has($class))->toBeFalse()->and(Singleton::getOrNull($class))->toBeNull();
    $first = Singleton::get($class);
    expect(Singleton::get($class))->toBe($first)->and(Singleton::has($class))->toBeTrue()
        ->and(Singleton::getOrNull($class))->toBe($first);
    $second = Singleton::make($class);
    expect($second)->not->toBe($first)->and(Singleton::get($class))->toBe($second);
});

it('evaluates a registered factory only once', function (): void {
    $class = new class
    {
    }::class;
    $calls = 0;
    $factory = function () use (&$calls): object {
        $calls++;
        return new stdClass();
    };
    $value = Singleton::register($class, $factory);
    expect(Singleton::register($class, $factory))->toBe($value)->and(Singleton::get($class))->toBe($value)->and($calls)->toBe(1);
});
