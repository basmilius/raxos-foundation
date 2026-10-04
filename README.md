<a href="https://bas.dev">
    <img src="https://bmcdn.nl/assets/branding/logo.svg" alt="Bas Milius" height="48" />
</a>

---

# Raxos Foundation

Shared value objects, access traits and utility functions used throughout Raxos.

[Documentation](https://raxos.dev/foundation/) | [Packagist](https://packagist.org/packages/raxos/foundation) | [Raxos](https://github.com/basmilius/raxos)

- `Option`, `Some` and `None` for optional values.
- IP parsing and array/magic-property access traits.
- Array, string, color, filesystem, math, reflection and XML utilities.
- Singleton instances, stopwatches and preloading helpers.

## Installation

Requires PHP 8.5 or later. Enable the `dom`, `intl`, `json`, `mbstring`, `openssl`, `simplexml` PHP extensions. Composer checks the remaining package and extension dependencies declared in [composer.json](composer.json).

```sh
composer require "raxos/foundation:^3.3"
```

## Usage

```php
<?php
declare(strict_types=1);

use Raxos\Foundation\Network\IP;
use Raxos\Foundation\Option\Option;

require __DIR__ . '/vendor/autoload.php';

$ip = IP::parse('203.0.113.10');
$label = Option::fromValue($ip?->value)
    ->map(static fn(string $value): string => "Client {$value}")
    ->getOrElse('Unknown client');

echo $label;
```

`Option::fromValue()` treats `null` as absent by default; values such as `0`, `false` and an empty string remain present. Pass a different sentinel as the second argument when needed.

## Documentation

- [Option type](https://raxos.dev/foundation/option)
- [Network: IP](https://raxos.dev/foundation/network)
- [Util classes](https://raxos.dev/foundation/utilities)
- [Singleton, Stopwatch and global functions](https://raxos.dev/foundation/singleton-and-stopwatch)

## Testing

Run this library's Pest suite from the Raxos workspace:

```sh
git clone --recurse-submodules https://github.com/basmilius/raxos.git
cd raxos
composer install
vendor/bin/pest --testsuite=foundation
```

See [Testing Raxos](https://github.com/basmilius/raxos/blob/main/TESTING.md) for PHP extensions, integration services and coverage commands. The library's [Tests workflow](.github/workflows/tests.yml) also runs in GitHub Actions.

## License

[MIT](LICENSE). Copyright (c) 2017 - present Bas Milius.
