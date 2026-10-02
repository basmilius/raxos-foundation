<?php
declare(strict_types=1);

use Raxos\Foundation\Option\{Option, Some};

covers(Some::class);

it('preserves its value without evaluating fallback or exception factories', function (mixed $value): void {
    $some = new Some($value);
    $unexpected = static fn (): never => throw new LogicException('Unexpected fallback');
    expect($some->isEmpty)->toBeFalse()->and($some->get())->toBe($value)->and($some->getOrElse('fallback'))->toBe($value)
        ->and($some->getOrInvoke($unexpected))->toBe($value)->and($some->getOrThrow($unexpected))->toBe($value)
        ->and($some->orElse($unexpected))->toBe($some)->and($some->orThrow($unexpected))->toBe($some)
        ->and($some->__debugInfo())->toBe(['type' => Some::class, 'value' => $value]);
})->with([[null], [false], [0], [''], [[]]]);

it('maps values and accepts only strict predicate success and equality', function (): void {
    $some = new Some(0);
    expect($some->map(static fn (int $value): int => $value + 1)->get())->toBe(1)
        ->and($some->filter(static fn (): bool => true))->toBe($some)
        ->and($some->filter(static fn (): int => 1))->toBe(Option::none())
        ->and($some->accept(0))->toBe($some)->and($some->accept(false))->toBe(Option::none())
        ->and($some->reject(false))->toBe($some)->and($some->reject(0))->toBe(Option::none());
});
