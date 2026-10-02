<?php
declare(strict_types=1);

use Raxos\Foundation\Util\ColorUtil;

covers(ColorUtil::class);

it('converts hex formats including shorthand and alpha', function (string $hex, array $expected): void {
    expect(ColorUtil::hexToRgba($hex))->toEqual($expected)->and(ColorUtil::hexToRgb($hex))->toEqual(array_slice($expected, 0, 3));
})->with([['#ff0000', [255, 0, 0, 1]], [' Ff00FF80 ', [255, 0, 255, 128 / 255]], ['#0f8', [0, 255, 136, 1]]]);

it('rejects invalid hex values', function (string $value): void {
    expect(fn () => ColorUtil::hexToRgba($value))->toThrow(InvalidArgumentException::class);
})->with(['', '#12', '#abcd', '#gggggg', '#123456789']);

it('converts integers, hex values and alpha channels', function (): void {
    expect(ColorUtil::intToRgb(0x123456))->toBe([18, 52, 86])->and(ColorUtil::rgbToInt(18, 52, 86))->toBe(0x123456)
        ->and(ColorUtil::intToRgba(0x80123456))->toBe([18, 52, 86, 128])
        ->and(ColorUtil::rgbToHex(0, 15, 255))->toBe('000fff')->and(ColorUtil::rgbToHex(0, 15, 255, true))->toBe('#000fff')
        ->and(ColorUtil::rgbaToHex(0, 15, 255, 0.5, true))->toBe('#000fff80');
});

it('converts all hue sectors and achromatic colors', function (array $rgb, array $hsl): void {
    expect(ColorUtil::rgbToHsl(...$rgb))->toEqual($hsl);
    $roundTrip = ColorUtil::hslToRgb(...$hsl);
    foreach ($rgb as $index => $channel) {
        expect(abs($roundTrip[$index] - $channel))->toBeLessThanOrEqual(1);
    }
})->with([
    [[255, 0, 0], [0.0, 1.0, 0.5]], [[255, 255, 0], [0.167, 1.0, 0.5]], [[0, 255, 0], [0.333, 1.0, 0.5]],
    [[0, 255, 255], [0.5, 1.0, 0.5]], [[0, 0, 255], [0.667, 1.0, 0.5]], [[255, 0, 255], [0.833, 1.0, 0.5]],
    [[0, 0, 0], [0.0, 0.0, 0.0]], [[255, 255, 255], [0.0, 0.0, 1.0]]
]);

it('blends, shades and tints colors at boundary weights', function (): void {
    expect(ColorUtil::blend([255, 0, 0], [0, 0, 255], 50))->toBe([128, 0, 128, 1])
        ->and(ColorUtil::blend([255, 0, 0], [0, 0, 255], -10))->toBe([0, 0, 255, 1])
        ->and(ColorUtil::blend([255, 0, 0], [0, 0, 255], 110))->toBe([255, 0, 0, 1])
        ->and(ColorUtil::shade([100, 100, 100], 50))->toBe([50, 50, 50, 1])
        ->and(ColorUtil::tint([0, 0, 0], 100))->toBe([255, 255, 255, 1]);
});

it('selects readable foreground colors using relative luminance', function (): void {
    expect(ColorUtil::luminance(0, 0, 0))->toBe(0.0)->and(ColorUtil::luminance(255, 255, 255))->toBe(1.0)
        ->and(ColorUtil::lightOrDark([0, 0, 0]))->toBe([255, 255, 255])
        ->and(ColorUtil::lightOrDark([255, 255, 255]))->toBe([0, 0, 0])
        ->and(ColorUtil::yiq(255, 255, 255))->toBe(255.0);
});
