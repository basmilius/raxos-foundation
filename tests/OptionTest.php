<?php
declare(strict_types=1);

use Raxos\Foundation\Option\Option;
use Raxos\Foundation\Option\OptionException;

it('keeps falsey values as Some while recognizing the configured None sentinel', function (mixed $value): void {
    $option = Option::fromValue($value);
    expect($option->isEmpty)->toBeFalse()->and($option->get())->toBe($value);
})->with([[false], [0], [''], [[]]]);

it('recognizes None, preserves existing options and evaluates factories once', function (): void {
    $none = Option::none();
    expect(Option::fromValue(null))->toBe($none)
        ->and(Option::fromValue('missing', 'missing'))->toBe($none)
        ->and(Option::fromValue($none))->toBe($none)
        ->and(Option::fromCallable(static fn(): int => 7)->get())->toBe(7)
        ->and(fn(): mixed => $none->get())->toThrow(OptionException::class);
});

it('maps and filters Some and does not execute callbacks for None', function (): void {
    $some = Option::some(3);
    $none = Option::none();
    $fail = static fn(mixed $value): never => throw new RuntimeException('Unexpected callback.');
    expect($some->map(static fn(int $value): int => $value * 2)->get())->toBe(6)
        ->and($some->filter(static fn(int $value): bool => $value === 3))->toBe($some)
        ->and($some->filter(static fn(int $value): bool => $value > 3))->toBe($none)
        ->and($none->map($fail))->toBe($none)
        ->and($none->filter($fail))->toBe($none);
});

it('only invokes fallbacks for None and validates option fallbacks', function (): void {
    $some = Option::some('value');
    $none = Option::none();
    $fail = static fn(): never => throw new RuntimeException('Unexpected fallback.');
    expect($some->getOrElse('fallback'))->toBe('value')
        ->and($some->getOrInvoke($fail))->toBe('value')
        ->and($some->orElse($fail))->toBe($some)
        ->and($none->getOrElse('fallback'))->toBe('fallback')
        ->and($none->getOrInvoke(static fn(): string => 'fallback'))->toBe('fallback')
        ->and($none->orElse(static fn(): Raxos\Foundation\Contract\OptionInterface => $some))->toBe($some)
        ->and(fn(): mixed => $none->orElse(static fn(): int => 3))->toThrow(OptionException::class)
        ->and(fn(): mixed => $none->getOrThrow(new RuntimeException('missing')))->toThrow(RuntimeException::class, 'missing')
        ->and(fn(): mixed => $none->orThrow(static fn(): RuntimeException => new RuntimeException('missing')))->toThrow(RuntimeException::class);
});

it('accepts and rejects values using strict equality', function (): void {
    $some = Option::some(0);
    expect($some->accept(0))->toBe($some)
        ->and($some->accept(false)->isEmpty)->toBeTrue()
        ->and($some->reject(false))->toBe($some)
        ->and($some->reject(0)->isEmpty)->toBeTrue();
});
