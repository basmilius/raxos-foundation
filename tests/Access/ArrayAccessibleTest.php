<?php
declare(strict_types=1);

namespace Access;

use ArrayAccess;
use Raxos\Foundation\Access\ArrayAccessible;
use function expect;
use function it;

it('should be able to access class properties as if the class was an associative array.', function (): void {
    $class = new class implements ArrayAccess {
        use ArrayAccessible;

        public function getValue(string $key): string
        {
            return "value for key: {$key}";
        }

        public function hasValue(string $key): bool
        {
            return $key !== 'b';
        }

        public function setValue(string $key, mixed $value): void {}

        public function unsetValue(string $key): void {}
    };

    expect($class['a'])->toBe('value for key: a')
        ->and(isset($class['b']))->toBeFalse()
        ->and(isset($class['c']))->toBeTrue();
});
