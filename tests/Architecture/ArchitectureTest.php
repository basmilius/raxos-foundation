<?php
declare(strict_types=1);

use Raxos\Foundation\Error\ExceptionId;
use Raxos\Foundation\Util\Debug;

arch()
    ->preset()
    ->php()
    ->ignoring([
        // note: This class uses die().
        Debug::class,

        // note: This class uses debug_backtrace().
        ExceptionId::class
    ]);

arch()
    ->preset()
    ->security()
    ->ignoring([
        'array_rand',
        'shuffle',
        'str_shuffle'
    ]);

arch()
    ->expect('Raxos\Foundation')
    ->toUseStrictTypes()
    ->toUseStrictEquality();

arch()
    ->expect('Raxos\Foundation\Contract')
    ->toBeInterfaces();
