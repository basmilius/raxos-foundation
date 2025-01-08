<?php
declare(strict_types=1);

namespace Util;

use Generator;
use Raxos\Foundation\Collection\ArrayList;
use Raxos\Foundation\Util\ArrayUtil;
use function describe;
use function expect;
use function it;

describe('ensureArray', function (): void {
    it('should be able to return an array from an array.', function (): void {
        $arr = [1, 2, 3];
        $result = ArrayUtil::ensureArray($arr);

        expect($result)->toBe($arr);
    });

    it('should be able to return an array from an array list.', function (): void {
        $list = new ArrayList([1, 2, 3]);
        $result = ArrayUtil::ensureArray($list);

        expect($result)->toBe([1, 2, 3]);
    });

    it('should be able to return an array from a generator.', function (): void {
        $generator = function (): Generator {
            for ($i = 1; $i <= 3; $i++) {
                yield $i;
            }
        };

        $result = ArrayUtil::ensureArray($generator());

        expect($result)->toBe([1, 2, 3]);
    });
});

describe('flatten', function (): void {
    it('should be able to flatten an array.', function (): void {
        $arr = [1, 2, [3, 4]];
        $result = ArrayUtil::flatten($arr);

        expect($result)->toBe([1, 2, 3, 4]);
    });

    it('should not exceed the maximum depth.', function (): void {
        $arr = [1, 2, [3, 4, [5, 6]]];
        $result = ArrayUtil::flatten($arr, 1);

        expect($result)->toBe([1, 2, 3, 4, [5, 6]]);
    });
});

describe('groupBy', function (): void {
    it('should be able to group an array by a key.', function (): void {
        $arr = [
            ['id' => 1, 'name' => 'John', 'age' => 20],
            ['id' => 2, 'name' => 'Jane', 'age' => 21],
            ['id' => 3, 'name' => 'Jill', 'age' => 20]
        ];
        $result = ArrayUtil::groupBy($arr, 'age');

        expect($result)->toBe([
            [
                ['id' => 1, 'name' => 'John', 'age' => 20],
                ['id' => 3, 'name' => 'Jill', 'age' => 20]
            ],
            [
                ['id' => 2, 'name' => 'Jane', 'age' => 21]
            ]
        ]);
    });
});

describe('in', function (): void {
    it('should be able to check if any of the values are in the array.', function (): void {
        $search = [2, 3];
        $arr = [1, 2, 3, 4];
        $result = ArrayUtil::in($arr, $search);

        expect($result)->toBeTrue();

        $search = [2, 8];
        $result = ArrayUtil::in($arr, $search);

        expect($result)->toBeTrue();

        $search = [5, 6];
        $result = ArrayUtil::in($arr, $search);

        expect($result)->toBeFalse();
    });

    it('should be able to check if all of the values are in the array.', function (): void {
        $search = [2, 3];
        $arr = [1, 2, 3, 4];
        $result = ArrayUtil::in($arr, $search, true);

        expect($result)->toBeTrue();

        $search = [2, 9];
        $result = ArrayUtil::in($arr, $search, true);

        expect($result)->toBeFalse();
    });
});

describe('only', function (): void {
    it('should be able to return only the specified keys from an array.', function (): void {
        $arr = [
            'id' => 1,
            'name' => 'John',
            'age' => 20
        ];
        $result = ArrayUtil::only($arr, ['id', 'name']);

        expect($result)->toBe([
            'id' => 1,
            'name' => 'John'
        ]);
    });
});

describe('first', function (): void {
    it('should be able to return the first item from an array.', function (): void {
        $arr = [1, 2, 3];
        $result = ArrayUtil::first($arr);

        expect($result)->toBe(1);
    });

    it('should be able to return the first item matching a predicate from an array.', function (): void {
        $arr = [1, 2, 3];
        $result = ArrayUtil::first($arr, fn($item) => $item === 2);

        expect($result)->toBe(2);
    });
});

describe('last', function (): void {
    it('should be able to return the last item from an array.', function (): void {
        $arr = [1, 2, 3];
        $result = ArrayUtil::last($arr);

        expect($result)->toBe(3);
    });

    it('should be able to return the last item matching a predicate from an array.', function (): void {
        $arr = [1, 2, 3];
        $result = ArrayUtil::last($arr, fn($item) => $item === 2);

        expect($result)->toBe(2);
    });
});
