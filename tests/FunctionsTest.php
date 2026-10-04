<?php
declare(strict_types=1);

use function Raxos\Foundation\{env, isBuiltInServer, isCommandLineInterface, singleton};

it('reads typed environment defaults and preserves falsey strings', function (string $raw, string|bool|int|null $default, string|bool|int|null $expected): void {
    $name = 'RAXOS_UNIT_ENV';
    $previous = getenv($name);
    try {
        putenv($name . '=' . $raw);
        expect(env($name, $default))->toBe($expected);
    } finally {
        putenv($previous === false ? $name : $name . '=' . $previous);
    }
})->with([['0', null, '0'], ['', null, ''], ['42', 0, 42], ['yes', false, true], ['ON', false, true], ['false', true, false], ['invalid', true, false]]);

it('uses defaults for missing environment variables and identifies the CLI', function (): void {
    expect(env('RAXOS_UNIT_ENV_MISSING_7BDA', 'fallback'))->toBe('fallback')
        ->and(isCommandLineInterface())->toBeTrue()->and(isBuiltInServer())->toBeFalse();
});

it('returns the same singleton with and without a factory', function (): void {
    $class = new class {}::class;
    $value = singleton($class);
    expect(singleton($class, static fn(): never => throw new LogicException('Already exists')))->toBe($value);
    $other = new class {}::class;
    $expected = new stdClass();
    expect(singleton($other, static fn() => $expected))->toBe($expected)->and(singleton($other))->toBe($expected);
});
