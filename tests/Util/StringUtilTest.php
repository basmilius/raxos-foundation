<?php
declare(strict_types=1);

use Raxos\Foundation\Util\StringUtil;

covers(StringUtil::class);

it('formats byte and bit quantities with configured units and rounding', function (int $bytes, bool $si, bool $bits, string $expected): void {
    expect(StringUtil::formatBytes($bytes, 2, $si, $bits))->toBe($expected);
})->with([[0, true, false, '0 B'], [1023, true, false, '1023 B'], [1024, true, false, '1 kB'], [1536, false, false, '1.5 KiB'], [128, false, true, '1 Kib'], [1048576, false, false, '1 MiB']]);

it('recognizes PHP serialization envelopes without accepting unrelated text', function (string $value, bool $expected): void {
    expect(StringUtil::isSerialized($value))->toBe($expected);
})->with([['N;', true], ['b:0;', true], ['i:-12;', true], ['d:1.25;', true], ['s:1:"x";', true], ['a:0:{}', true], ['O:8:"stdClass":0:{}', true], ['plain', false], ['s:1:"x"', false], ['i:no;', false], ['', false]]);

it('replaces Unicode substrings by character position', function (): void {
    expect(StringUtil::multiByteSubstringReplace('a😀éb', 'X', 1, 2))->toBe('aXb')
        ->and(StringUtil::multiByteSubstringReplace('a😀éb', 'X', 1, 0))->toBe('aX😀éb');
});

it('generates strings using each requested alphabet and optional separators', function (string $sets, string $pattern): void {
    $value = StringUtil::random(16, sets: $sets);
    expect($value)->toHaveLength(16)->toMatch($pattern);
    $dashed = StringUtil::random(16, true, $sets);
    expect(str_replace('-', '', $dashed))->toHaveLength(16)->toMatch($pattern)
        ->and(explode('-', $dashed))->toHaveCount(4);
})->with([['l', '/^[a-z]+$/'], ['u', '/^[A-Z]+$/'], ['d', '/^[1-9]+$/'], ['s', '/^[!@#$%&*?]+$/']]);

it('formats human lists and extracts names from qualified classes', function (): void {
    expect(StringUtil::commaCommaAnd([]))->toBe('')->and(StringUtil::commaCommaAnd(['a']))->toBe('a')
        ->and(StringUtil::commaCommaAnd(['a', 'b']))->toBe('a & b')
        ->and(StringUtil::shortClassName('Raxos\\Foundation\\Util\\StringUtil'))->toBe('StringUtil')
        ->and(StringUtil::shortClassName('PlainClass'))->toBe('PlainClass');
});

it('normalizes case and transliterates Unicode slugs', function (string $input, string $expected): void {
    expect(StringUtil::slugify($input))->toBe($expected);
})->with([[' Héllö, Raxos! ', 'hello-raxos'], ['one---two', 'one-two'], ['!!!', ''], ['', ''], ['Crème brûlée', 'creme-brulee']]);

it('splits sentences while retaining common abbreviations and ellipses', function (): void {
    expect(StringUtil::splitSentences('Dr. Smith waits... Then leaves. Next sentence! Last?'))->toBe(['Dr. Smith waits... Then leaves.', 'Next sentence!', 'Last?'])
        ->and(StringUtil::splitSentences(''))->toBe([])
        ->and(StringUtil::toPascalCase('foo-bar_baz 12'))->toBe('FooBarBaz12')
        ->and(StringUtil::toSnakeCase('HTTPServerID'))->toBe('http_server_id');
});

it('truncates readable text after removing headings and markup', function (): void {
    expect(StringUtil::truncateText('<h2>Heading</h2><h3>Subheading</h3><p>one two, three four</p>', 2, '…'))->toBe('one two…')
        ->and(StringUtil::truncateText('<p>one two</p>', 2))->toBe('one two')
        ->and(StringUtil::truncateText('', 2))->toBe('');
});
