<?php
declare(strict_types=1);

use Raxos\Foundation\Util\FileSystemUtil;

covers(FileSystemUtil::class);

it('creates unique writable temporary files that callers can remove', function (): void {
    $first = FileSystemUtil::temporaryFile();
    $second = FileSystemUtil::temporaryFile();
    try {
        expect($first)->not->toBe($second)->and(is_file($first))->toBeTrue()->and(is_writable($first))->toBeTrue();
        file_put_contents($first, 'unit');
        expect(file_get_contents($first))->toBe('unit');
    } finally {
        unlink($first);
        unlink($second);
    }
});

it('creates a temporary stream that is removed when closed', function (): void {
    $stream = FileSystemUtil::temporaryFileStream();
    $path = stream_get_meta_data($stream)['uri'];
    try {
        expect(is_resource($stream))->toBeTrue();
        fwrite($stream, 'unit');
        rewind($stream);
        expect(stream_get_contents($stream))->toBe('unit');
    } finally {
        fclose($stream);
    }
    expect(file_exists($path))->toBeFalse();
});
