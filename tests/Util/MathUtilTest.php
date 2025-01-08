<?php
declare(strict_types=1);

namespace Util;

use Raxos\Foundation\Util\MathUtil;
use function describe;
use function expect;

describe('clamp', function (): void {
    it('should return return the original number if it is between the minimum and maximum.', function (): void {
        expect((int)MathUtil::clamp(10, 0, 100))->toBe(10);
    });

    it('should return the minimum if the number is less than the minimum.', function (): void {
        expect((int)MathUtil::clamp(-10, 0, 100))->toBe(0);
    });

    it('should return the maximum if the number is greater than the maximum.', function (): void {
        expect((int)MathUtil::clamp(1000, 0, 100))->toBe(100);
    });
});

describe('ceilStep', function (): void {
    it('should return the original number if it is a multiple of the step.', function (): void {
        expect((int)MathUtil::ceilStep(10, 5))->toBe(10);
    });

    it('should return the next multiple of the step if the number is not a multiple of the step.', function (): void {
        expect((int)MathUtil::ceilStep(14, 5))->toBe(15);
    });
});

describe('floorStep', function (): void {
    it('should return the original number if it is a multiple of the step.', function (): void {
        expect((int)MathUtil::floorStep(10, 5))->toBe(10);
    });

    it('should return the previous multiple of the step if the number is not a multiple of the step.', function (): void {
        expect((int)MathUtil::floorStep(14, 5))->toBe(10);
    });
});

describe('roundStep', function (): void {
    it('should return the original number if it is a multiple of the step.', function (): void {
        expect((int)MathUtil::roundStep(10, 5))->toBe(10);
    });

    it('should return the next multiple of the step if the number is not a multiple of the step and closer to the next step.', function (): void {
        expect((int)MathUtil::roundStep(14, 5))->toBe(15);
    });

    it('should return the previous multiple of the step if the number is not a multiple of the step and closer to the previous step.', function (): void {
        expect((int)MathUtil::roundStep(12, 5))->toBe(10);
    });
});

describe('greatestCommonDivisor', function (): void {
    it('should return the greatest common divisor of the two numbers.', function (): void {
        expect((int)MathUtil::greatestCommonDivisor(10, 20))->toBe(10);
    });
});

describe('simplifyFraction', function (): void {
    it('should return the simplified fraction.', function (): void {
        expect(MathUtil::simplifyFraction(8, 16))->toBe([1, 2]);
    });
});
