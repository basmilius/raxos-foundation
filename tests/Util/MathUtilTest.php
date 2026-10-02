<?php
declare(strict_types=1);

use Raxos\Foundation\Util\MathUtil;

covers(MathUtil::class);

it('clamps values while preserving numeric types', function (int|float $value, int|float $expected): void {
    expect(MathUtil::clamp($value, 0, 10))->toBe($expected);
})->with([[-1, 0], [0, 0], [3.5, 3.5], [10, 10], [11, 10]]);

it('rounds positive and negative values to a step', function (float $value, float $ceil, float $floor, float $round): void {
    expect(MathUtil::ceilStep($value, 0.5))->toBe($ceil)->and(MathUtil::floorStep($value, 0.5))->toBe($floor)
        ->and(MathUtil::roundStep($value, 0.5))->toBe($round);
})->with([[1.2, 1.5, 1.0, 1.0], [-1.2, -1.0, -1.5, -1.0], [1.25, 1.5, 1.0, 1.5], [0.0, 0.0, 0.0, 0.0]]);

it('finds common divisors and reduces signed fractions', function (int $a, int $b, int $gcd): void {
    expect(MathUtil::greatestCommonDivisor($a, $b))->toBe($gcd);
})->with([[54, 24, 6], [24, 54, 6], [-54, 24, 6], [0, 8, 8], [8, 0, 8], [0, 0, 0], [13, 7, 1]]);

it('simplifies fractions to the smallest terms', function (): void {
    expect(MathUtil::simplifyFraction(12, 18))->toBe([2, 3])->and(MathUtil::simplifyFraction(-12, 18))->toBe([-2, 3]);
});
