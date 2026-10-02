<?php
declare(strict_types=1);

use Raxos\Foundation\Preloader;

covers(Preloader::class);

it('loads PHP files once while excluding templates, tests, hidden files and ignored paths', function (): void {
    $directory = sys_get_temp_dir() . '/raxos-preloader-' . bin2hex(random_bytes(6));
    mkdir($directory);
    mkdir($directory . '/nested');
    mkdir($directory . '/tests');
    $files = ['unit.php', 'nested/child.php', '.hidden.php', 'autoload.php', 'template.html.php', 'data.json.php', '.phpstorm.meta.php', 'tests/test.php', 'ignore.php', 'plain.txt'];
    $GLOBALS['raxos_preloader_unit'] = [];
    foreach ($files as $file) {
        file_put_contents($directory . '/' . $file, '<?php $GLOBALS["raxos_preloader_unit"][] = ' . var_export($file, true) . ';');
    }
    try {
        $preloader = new Preloader([$directory . '/']);
        $preloader->path($directory . '/missing.php');
        $preloader->ignore(strtoupper($directory . '/ignore.php'));
        $preloader->preload();
        $preloader->preload();
        $loaded = $GLOBALS['raxos_preloader_unit'];
        sort($loaded);
        expect($loaded)->toBe(['nested/child.php', 'unit.php']);
    } finally {
        foreach ($files as $file) {
            unlink($directory . '/' . $file);
        }
        rmdir($directory . '/nested');
        rmdir($directory . '/tests');
        rmdir($directory);
        unset($GLOBALS['raxos_preloader_unit']);
    }
});
