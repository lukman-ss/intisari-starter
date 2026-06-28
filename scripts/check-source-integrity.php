<?php

declare(strict_types=1);

$basePath = dirname(__DIR__);

function fail(string $message): void {
    echo "FAIL: $message\n";
    exit(1);
}

function pass(string $message): void {
    echo "PASS: $message\n";
}

// 1. PHP files checks
$dirs = ['app', 'bootstrap', 'config', 'public', 'routes', 'scripts', 'tests'];
foreach ($dirs as $dir) {
    $path = $basePath . DIRECTORY_SEPARATOR . $dir;
    if (!is_dir($path)) continue;
    
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $content = file_get_contents($file->getPathname());
            if (trim($content) === '') fail("File is empty: " . $file->getPathname());
            if (!str_starts_with(ltrim($content), '<?php')) fail("Does not start with <?php: " . $file->getPathname());
        }
    }
}

// 2. intisari check
$intisari = $basePath . DIRECTORY_SEPARATOR . 'intisari';
if (is_file($intisari)) {
    $content = file_get_contents($intisari);
    if (trim($content) === '') fail("intisari is empty");
    if (!str_contains($content, '<?php')) fail("intisari missing <?php");
}

// 3. config check
$configDir = $basePath . DIRECTORY_SEPARATOR . 'config';
if (is_dir($configDir)) {
    foreach (scandir($configDir) as $file) {
        if (str_ends_with($file, '.php')) {
            $content = file_get_contents($configDir . DIRECTORY_SEPARATOR . $file);
            if (!str_contains($content, 'return')) fail("Config $file missing return");
        }
    }
}

// 4. phpunit.xml check
$phpunit = $basePath . DIRECTORY_SEPARATOR . 'phpunit.xml';
if (is_file($phpunit)) {
    $prev = libxml_use_internal_errors(true);
    if (simplexml_load_file($phpunit) === false) fail("phpunit.xml invalid");
    libxml_clear_errors();
    libxml_use_internal_errors($prev);
}

// 5. .env.example check
$envExample = $basePath . DIRECTORY_SEPARATOR . '.env.example';
if (!is_file($envExample)) fail(".env.example missing");
$envContent = file_get_contents($envExample);
if (substr_count($envContent, "\n") < 2) fail(".env.example not multiline");
if (!str_contains($envContent, 'APP_NAME=')) fail(".env.example missing APP_NAME=");
if (!str_contains($envContent, 'DB_CONNECTION=')) fail(".env.example missing DB_CONNECTION=");

// 6. .github workflows
$workflows = $basePath . DIRECTORY_SEPARATOR . '.github' . DIRECTORY_SEPARATOR . 'workflows';
if (is_dir($workflows)) {
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($workflows));
    foreach ($iterator as $file) {
        if ($file->isFile() && (str_ends_with($file->getFilename(), '.yml') || str_ends_with($file->getFilename(), '.yaml'))) {
            $content = file_get_contents($file->getPathname());
            if (substr_count($content, "\n") < 2) fail("Workflow {$file->getFilename()} not multiline");
        }
    }
}

// 7. docs check
$docsDir = $basePath . DIRECTORY_SEPARATOR . 'docs';
if (is_dir($docsDir)) {
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($docsDir));
    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'md') {
            $content = file_get_contents($file->getPathname());
            $forbidden = ['file:///', 'C:\\', 'D:\\', '/Users/'];
            foreach ($forbidden as $f) {
                if (stripos($content, $f) !== false) fail("Docs contain forbidden path $f in " . $file->getPathname());
            }
        }
    }
}

pass("Source integrity verified.");
