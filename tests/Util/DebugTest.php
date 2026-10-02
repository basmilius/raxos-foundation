<?php
declare(strict_types=1);

use Raxos\Foundation\Util\Debug;

covers(Debug::class);

it('prints CLI values without markup and preserves JSON scalar and multi-value shapes', function (): void {
    ob_start();
    Debug::print(['unit' => 42]);
    $printed = ob_get_clean();
    expect($printed)->toContain('[unit] => 42')->not->toContain('<pre>');
    ob_start();
    Debug::dump(false);
    expect(ob_get_clean())->toBe("bool(false)\n");
    ob_start();
    Debug::json('<tag>&');
    expect(json_decode(ob_get_clean(), true))->toBe('<tag>&');
    ob_start();
    Debug::json(0, false);
    expect(json_decode(ob_get_clean(), true))->toBe([0, false]);
});

it('reports current and peak memory in MiB', function (): void {
    [$current, $peak] = Debug::ramUsage();
    expect($current)->toMatch('/^RAM Usage: \d+\.\d{5} MiB$/')->and($peak)->toMatch('/^RAM Peak:  \d+\.\d{5} MiB$/');
});
