<?php
declare(strict_types=1);

use Raxos\Foundation\Util\ColorUtil;
use Raxos\Foundation\Util\Stopwatch;
use Raxos\Foundation\Util\StopwatchState;
use Raxos\Foundation\Util\StopwatchUnit;
use Raxos\Foundation\Util\StringUtil;

it('measures a return value without requiring a description', function (): void {
    $duration = 0.0;
    expect(Stopwatch::measure($duration, static fn() => 42, StopwatchUnit::MILLISECONDS))->toBe(42);
    expect($duration)->toBeGreaterThanOrEqual(0.0);
});

it('stops a measurement when the callback fails', function (): void {
    $watch = new Stopwatch();
    expect(fn() => $watch->run(static fn() => throw new RuntimeException('failure')))->toThrow(RuntimeException::class);
    expect($watch->state)->toBe(StopwatchState::STOPPED);
});

it('retains fractional alpha in hexadecimal colors', function (): void {
    expect(ColorUtil::rgbaToHex(255, 0, 0, 0.5))->toBe('ff000080');
    expect(ColorUtil::rgbaToHex(255, 0, 0, 0.0))->toBe('ff000000');
    expect(ColorUtil::rgbaToHex(255, 0, 0, 1.0))->toBe('ff0000ff');
});

it('uses the same RGB scale for gray and saturated colors', function (): void {
    expect(ColorUtil::hslToRgb(0.0, 0.0, 0.5))->toBe([128.0, 128.0, 128.0]);
    expect(ColorUtil::hslToRgb(0.0, 1.0, 0.5))->toBe([255.0, 0.0, 0.0]);
});

it('generates the requested number of random characters', function (int $length): void {
    expect(strlen(StringUtil::random($length)))->toBe($length);
})->with([1, 3, 9, 16, 64]);
