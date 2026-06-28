<?php

declare(strict_types=1);

function fail(string $message): void {
    echo "FAIL: $message" . PHP_EOL;
    exit(1);
}

function pass(string $message): void {
    echo $message . PHP_EOL;
}

$basePath = dirname(__DIR__);
$directories = ['app', 'bootstrap', 'config', 'public', 'routes', 'scripts', 'tests'];

// 1. PHP files checks
foreach ($directories as $dir) {
    $dirPath = $basePath . DIRECTORY_SEPARATOR . $dir;
    if (!is_dir($dirPath)) continue;

    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dirPath));
    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $content = file_get_contents($file->getPathname());
            if (empty(trim($content))) {
                fail("File is empty: " . $file->getPathname());
            }
            if (!str_starts_with(ltrim($content), '<?php')) {
                fail("PHP file must start with <?php: " . $file->getPathname());
            }
        }
    }
}

// 2. intisari check
$intisariPath = $basePath . DIRECTORY_SEPARATOR . 'intisari';
if (is_file($intisariPath)) {
    $content = file_get_contents($intisariPath);
    if (empty(trim($content))) {
        fail("intisari file is empty");
    }
    if (!str_contains($content, '<?php')) {
        fail("intisari must contain <?php");
    }
}

// 3. config/*.php checks
$configDir = $basePath . DIRECTORY_SEPARATOR . 'config';
if (is_dir($configDir)) {
    foreach (scandir($configDir) as $file) {
        if (str_ends_with($file, '.php')) {
            $content = file_get_contents($configDir . DIRECTORY_SEPARATOR . $file);
            if (!str_contains($content, 'return ')) {
                fail("Config file must contain return: " . $file);
            }
        }
    }
}

// 4. phpunit.xml check
$phpunitXmlPath = $basePath . DIRECTORY_SEPARATOR . 'phpunit.xml';
if (is_file($phpunitXmlPath)) {
    $prev = libxml_use_internal_errors(true);
    if (simplexml_load_file($phpunitXmlPath) === false) {
        fail("phpunit.xml is not valid XML");
    }
    libxml_use_internal_errors($prev);
}

// 5. .env.example check
$envExamplePath = $basePath . DIRECTORY_SEPARATOR . '.env.example';
if (!is_file($envExamplePath)) {
    fail(".env.example does not exist");
}
$envContent = file_get_contents($envExamplePath);
$envLines = explode("\n", str_replace("\r", "", $envContent));
if (count($envLines) < 3) {
    fail(".env.example must contain multiple lines");
}
if (!str_contains($envContent, 'APP_NAME=')) {
    fail(".env.example must contain APP_NAME=");
}
if (!str_contains($envContent, 'DB_CONNECTION=')) {
    fail(".env.example must contain DB_CONNECTION=");
}

// 6. .github/workflows/*.yml check
$workflowsDir = $basePath . DIRECTORY_SEPARATOR . '.github' . DIRECTORY_SEPARATOR . 'workflows';
if (is_dir($workflowsDir)) {
    foreach (scandir($workflowsDir) as $file) {
        if (str_ends_with($file, '.yml') || str_ends_with($file, '.yaml')) {
            $content = file_get_contents($workflowsDir . DIRECTORY_SEPARATOR . $file);
            $lines = explode("\n", str_replace("\r", "", $content));
            if (count($lines) < 3) {
                fail("Workflow file must contain multiple lines: " . $file);
            }
            if (!preg_match('/^name:/m', $content)) {
                fail("Workflow file must contain 'name:': " . $file);
            }
            if (!preg_match('/^jobs:/m', $content)) {
                fail("Workflow file must contain 'jobs:': " . $file);
            }
        }
    }
}

// 7. Markdown files check and 8. Documentation checks
$mdIterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($basePath));
foreach ($mdIterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'md') {
        $pathname = $file->getPathname();
        if (str_contains($pathname, DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR)) {
            continue;
        }
        
        $content = file_get_contents($pathname);
        $lines = explode("\n", str_replace("\r", "", $content));
        if (count($lines) <= 2 && strlen($content) > 100) {
            fail("Markdown file seems collapsed into one line: " . $pathname);
        }

        // Docs checks
        if (str_contains(str_replace('\\', '/', $pathname), '/docs/')) {
            $invalidPatterns = ['file:///', 'C:\\', 'D:\\', '/Users/', str_replace('\\', '/', $basePath)];
            foreach ($invalidPatterns as $pattern) {
                if (stripos(str_replace('\\', '/', $content), str_replace('\\', '/', $pattern)) !== false) {
                    fail("Documentation contains invalid local path/string ($pattern): " . $pathname);
                }
            }
        }
    }
}

pass("Source Integrity Check PASSED.");
exit(0);
