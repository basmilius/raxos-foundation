<?php
declare(strict_types=1);

use Raxos\Foundation\Option\{None, Option, OptionException};

covers(None::class);

it('does not run transformations and has no value to extract', function (): void {
    $none = new None();
    $unexpected = static fn(): never => throw new LogicException('Unexpected transformation');
    expect($none->isEmpty)->toBeTrue()->and($none->map($unexpected))->toBe($none)->and($none->filter($unexpected))->toBe($none)
        ->and($none->accept(0))->toBe($none)->and($none->reject(0))->toBe($none)
        ->and($none->__debugInfo())->toBe(['type' => None::class, 'value' => null])
        ->and(fn() => $none->get())->toThrow(OptionException::class);
});

it('evaluates fallbacks only when requested and validates their option type', function (): void {
    $none = new None();
    $some = Option::some('unit');
    expect($none->getOrElse(false))->toBeFalse()->and($none->getOrInvoke(static fn(): int => 0))->toBe(0)
        ->and($none->orElse($some))->toBe($some)->and($none->orElse(static fn() => $some))->toBe($some)
        ->and(fn() => $none->orElse(static fn(): int => 1))->toThrow(OptionException::class);
});

it('throws supplied exceptions and exception factories without wrapping them', function (string $method, bool $factory): void {
    $error = new RuntimeException('missing');
    try {
        new None()->{$method}($factory ? static fn() => $error : $error);
        test()->fail('Expected the supplied exception.');
    } catch (RuntimeException $actual) {
        expect($actual)->toBe($error);
    }
})->with([['getOrThrow', false], ['getOrThrow', true], ['orThrow', false], ['orThrow', true]]);
