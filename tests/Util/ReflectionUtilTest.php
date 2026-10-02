<?php
declare(strict_types=1);

use Raxos\Foundation\Util\ReflectionUtil;

covers(ReflectionUtil::class);

it('indexes parameters by name while retaining reflection identity', function (): void {
    $function = new ReflectionFunction(static fn (int $first, string $second = ''): int => $first);
    $parameters = ReflectionUtil::getParameters($function);
    expect(array_keys($parameters))->toBe(['first', 'second'])->and($parameters['first']->getName())->toBe('first')
        ->and($parameters['second']->getDefaultValue())->toBe('');
});

it('returns every union type once, including nullable types', function (): void {
    $function = new ReflectionFunction(static fn (int|string|null $value): null => null);
    expect(ReflectionUtil::getTypes($function->getParameters()[0]->getType()))->toBe(['string', 'int', 'null'])
        ->and(ReflectionUtil::getTypes($function->getReturnType()))->toBe(['null'])
        ->and(iterator_to_array(ReflectionUtil::types($function->getParameters()[0]->getType(), true), false))->toBe(['string', 'int']);
});

it('flattens intersection and nullable named types', function (): void {
    $function = new ReflectionFunction(static fn (Countable&Iterator $value, ?int $other): mixed => $value);
    expect(ReflectionUtil::getTypes($function->getParameters()[0]->getType()))->toBe([Countable::class, Iterator::class])
        ->and(ReflectionUtil::getTypes($function->getParameters()[1]->getType()))->toBe(['int', 'null']);
});
