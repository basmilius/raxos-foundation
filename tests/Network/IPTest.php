<?php
declare(strict_types=1);

use Raxos\Foundation\Network\{IP, IPVersion};

covers(IP::class);

it('classifies valid addresses and rejects malformed, host and CIDR input', function (string $address, ?IPVersion $version): void {
    $ip = IP::parse($address);
    expect(IP::isValid($address))->toBe($version !== null)
        ->and(IP::isV4($address))->toBe($version === IPVersion::V4)
        ->and(IP::isV6($address))->toBe($version === IPVersion::V6)
        ->and($ip?->version)->toBe($version);
    if ($ip !== null) {
        expect((string)$ip)->toBe($address)->and($ip->jsonSerialize())->toBe($address)->and(IP::parse($address))->toBe($ip);
    }
})->with([['0.0.0.0', IPVersion::V4], ['192.168.0.1', IPVersion::V4], ['::', IPVersion::V6], ['::ffff:192.0.2.1', IPVersion::V6], ['01.2.3.4', null], ['1.2.3.4/24', null], ['localhost', null], ['::g', null]]);

it('continues parsing correctly after the bounded address cache evicts old entries', function (): void {
    for ($i = 0; $i < 1010; $i++) {
        IP::parse('2001:db8::' . dechex($i));
    }
    expect(IP::parse('2001:db8::1')?->version)->toBe(IPVersion::V6)->and(IP::parse('127.0.0.1')?->version)->toBe(IPVersion::V4);
});
