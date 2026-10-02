<?php
declare(strict_types=1);

use Raxos\Foundation\Access\ArrayAccessible;
use RaxosTests\Foundation\AccessStore;

covers(ArrayAccessible::class);

it('delegates array operations to the value store including null existence', function (): void {
    $store = new AccessStore();
    $store['unit'] = null;
    expect(isset($store['unit']))->toBeTrue()->and($store['unit'])->toBeNull()->and($store->hasValue('unit'))->toBeTrue();
    $store['unit'] = false;
    expect($store['unit'])->toBeFalse();
    unset($store['unit']);
    expect(isset($store['unit']))->toBeFalse()->and($store->hasValue('unit'))->toBeFalse();
});
