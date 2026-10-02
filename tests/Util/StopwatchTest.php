<?php
declare(strict_types=1);

use Raxos\Foundation\Util\{Stopwatch, StopwatchState, StopwatchUnit};

covers(Stopwatch::class);

it('exposes durations only after stopping and converts every unit', function (): void {
    $watch = new Stopwatch('unit');
    expect($watch->state)->toBe(StopwatchState::IDLE)->and($watch->format())->toBe('-')->and($watch->as(StopwatchUnit::SECONDS))->toBeNull();
    $watch->start();
    expect($watch->state)->toBe(StopwatchState::RUNNING)->and($watch->as(StopwatchUnit::SECONDS))->toBeNull();
    $watch->stop();
    $nanoseconds = $watch->stopTime - $watch->startTime;
    foreach ([[StopwatchUnit::NANOSECONDS, 1, 'ns'], [StopwatchUnit::MICROSECONDS, 1e3, 'μs'], [StopwatchUnit::MILLISECONDS, 1e6, 'ms'], [StopwatchUnit::SECONDS, 1e9, 's']] as [$unit, $divisor, $suffix]) {
        expect($watch->as($unit))->toBe($nanoseconds / $divisor)->and($watch->format($unit))->toEndWith($suffix);
    }
});

it('returns results and stops even when the measured callback fails', function (): void {
    $watch = new Stopwatch();
    expect($watch->run(static fn (): int => 42))->toBe(42)->and($watch->state)->toBe(StopwatchState::STOPPED);
    expect(fn () => $watch->run(static fn (): never => throw new RuntimeException('unit')))->toThrow(RuntimeException::class)
        ->and($watch->state)->toBe(StopwatchState::STOPPED)->and($watch->as(StopwatchUnit::SECONDS))->toBeGreaterThanOrEqual(0);
    $duration = 0.0;
    expect(Stopwatch::measure($duration, static fn (): string => 'unit', StopwatchUnit::SECONDS, 'test'))->toBe('unit')
        ->and($duration)->toBeGreaterThanOrEqual(0);
});
