<?php
declare(strict_types=1);

use Raxos\Error\InvalidArgumentException;
use Raxos\Foundation\Network\{IP, IPVersion};
use Raxos\Foundation\Util\{ArrayUtil, MathUtil, StringUtil};

it('classifies IPv4 and IPv6 without accepting malformed addresses', function (string $address, ?IPVersion $version): void {
    $ip = IP::parse($address);
    expect(IP::isValid($address))->toBe($version !== null)->and($ip?->version)->toBe($version);
    if ($ip !== null) {
        expect((string)$ip)->toBe($address)->and(json_encode($ip))->toBe(json_encode($address))->and(IP::parse($address))->toBe($ip);
    }
})->with([['127.0.0.1', IPVersion::V4], ['255.255.255.255', IPVersion::V4], ['::1', IPVersion::V6], ['2001:db8::1', IPVersion::V6], ['256.1.2.3', null], ['', null], ['example.org', null]]);

it('flattens to the requested depth and selects array values', function (): void {
    expect(ArrayUtil::flatten([1, [2, [3]]]))->toBe([1, 2, 3])
        ->and(ArrayUtil::flatten([1, [2, [3]]], 1))->toBe([1, 2, [3]])
        ->and(ArrayUtil::only(['a' => 1, 'b' => 2], ['b', 'missing']))->toBe(['b' => 2])
        ->and(ArrayUtil::ensureArray(new ArrayIterator([1, 2])))->toBe([1, 2])
        ->and(ArrayUtil::in([1, 2], [2, 3]))->toBeTrue()
        ->and(ArrayUtil::in([1, 2], [2, 3], true))->toBeFalse()
        ->and(ArrayUtil::first([1, 2, 3], static fn(int $value): bool => $value > 1))->toBe(2)
        ->and(ArrayUtil::last([1, 2, 3], static fn(int $value): bool => $value < 3))->toBe(2)
        ->and(ArrayUtil::first([], defaultValue: 'empty'))->toBe('empty');
});

it('rounds in steps and simplifies signed fractions', function (): void {
    expect(MathUtil::clamp(9, 0, 5))->toBe(5)
        ->and(MathUtil::clamp(-1, 0, 5))->toBe(0)
        ->and(MathUtil::ceilStep(1.2, 0.5))->toBe(1.5)
        ->and(MathUtil::floorStep(1.2, 0.5))->toBe(1.0)
        ->and(MathUtil::roundStep(1.2, 0.5))->toBe(1.0)
        ->and(MathUtil::greatestCommonDivisor(-12, 18))->toBe(6)
        ->and(MathUtil::greatestCommonDivisor(12, 0))->toBe(12)
        ->and(MathUtil::simplifyFraction(12, 18))->toEqual([2, 3]);
});

it('handles Unicode in slugging, replacements and word truncation', function (): void {
    expect(StringUtil::slugify(' Héllö, Raxos! '))->toBe('hello-raxos')
        ->and(StringUtil::multiByteSubstringReplace('a😀éb', 'X', 1, 2))->toBe('aXb')
        ->and(StringUtil::truncateText('<p>één twee drie vier</p>', 2))->toBe('één twee...')
        ->and(StringUtil::toSnakeCase('HTTPServerID'))->toBe('http_server_id')
        ->and(StringUtil::toPascalCase('foo-bar_baz'))->toBe('FooBarBaz')
        ->and(StringUtil::formatBytes(1024, siMode: false))->toBe('1 KiB')
        ->and(StringUtil::commaCommaAnd(['a', 'b', 'c']))->toBe('a, b & c');
});

it('rejects random strings without a positive length or an alphabet', function (int $length, string $sets): void {
    expect(fn(): string => StringUtil::random($length, sets: $sets))->toThrow(InvalidArgumentException::class);
})->with([[0, 'l'], [-1, 'l'], [4, ''], [4, 'xyz']]);
