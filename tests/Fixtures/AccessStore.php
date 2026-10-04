<?php
declare(strict_types=1);

namespace RaxosTests\Foundation;

use ArrayAccess;
use Raxos\Foundation\Access\{ArrayAccessible, ObjectAccessible};

final class AccessStore implements ArrayAccess
{

    use ArrayAccessible;
    use ObjectAccessible;

    private array $values = [];

    public function getValue(string $key): mixed
    {
        return $this->values[$key] ?? null;
    }

    public function hasValue(string $key): bool
    {
        return array_key_exists($key, $this->values);
    }

    public function setValue(string $key, mixed $value): void
    {
        $this->values[$key] = $value;
    }

    public function unsetValue(string $key): void
    {
        unset($this->values[$key]);
    }

}
