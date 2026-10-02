<?php
declare(strict_types=1);

use Raxos\Contract\Collection\ArrayableInterface;
use Raxos\Foundation\Util\ArrayUtil;

covers(ArrayUtil::class);

it('normalizes arrays, collection values and keyed iterators', function (): void {
    $arrayable = new class implements ArrayableInterface
    {
        public function toArray(): array
        {
            return ['a' => 1];
        }
    };

    expect(ArrayUtil::ensureArray(['a' => 1]))->toBe(['a' => 1])
        ->and(ArrayUtil::ensureArray($arrayable))->toBe(['a' => 1])
        ->and(ArrayUtil::ensureArray(new ArrayIterator(['a' => 1])))->toBe([1]);
});

it('flattens nested values to the requested depth', function (): void {
    expect(ArrayUtil::flatten([1, [2, [3]], null]))->toBe([1, 2, 3, null])
        ->and(ArrayUtil::flatten([1, [2, [3]]], 1))->toBe([1, 2, [3]])
        ->and(ArrayUtil::flatten([]))->toBe([]);
});

it('groups records in encounter order and selects existing fields', function (): void {
    $records = [['group' => 'a', 'id' => 1], ['group' => 'b', 'id' => 2], ['group' => 'a', 'id' => 3]];
    expect(ArrayUtil::groupBy($records, 'group'))->toBe([[$records[0], $records[2]], [$records[1]]])
        ->and(ArrayUtil::only(['a' => null, 'b' => false], ['a', 'missing']))->toBe(['a' => null]);
});

it('checks any and all membership including empty candidate lists', function (): void {
    expect(ArrayUtil::in([1, 2], [2, 3]))->toBeTrue()->and(ArrayUtil::in([1, 2], [2, 3], true))->toBeFalse()
        ->and(ArrayUtil::in([1, 2], [1, 2], true))->toBeTrue()->and(ArrayUtil::in([1], []))->toBeFalse()
        ->and(ArrayUtil::in([1], [], true))->toBeTrue();
});

it('finds first and last matching values with their original keys', function (): void {
    $values = ['a' => 0, 'b' => false, 'c' => 3];
    expect(ArrayUtil::first($values))->toBe(0)->and(ArrayUtil::last($values))->toBe(3)
        ->and(ArrayUtil::first($values, static fn (mixed $value, string $key): bool => $key === 'b'))->toBeFalse()
        ->and(ArrayUtil::last($values, static fn (mixed $value, string $key): bool => $key === 'a'))->toBe(0)
        ->and(ArrayUtil::first([], defaultValue: 'empty'))->toBe('empty')
        ->and(ArrayUtil::last($values, static fn (): bool => false, 'missing'))->toBe('missing');
});
