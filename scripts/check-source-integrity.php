<?php

declare(strict_types=1);

$projectRoot = realpath(dirname(__DIR__));

if ($projectRoot === false) {
    fwrite(STDERR, "Unable to resolve project root.\n");
    exit(1);
}

$errors = [];

// 1. Check PHP files in specified directories
$phpDirs = ['app', 'bootstrap', 'config', 'public', 'routes', 'scripts', 'tests'];
foreach ($phpDirs as $dir) {
    $dirPath = $projectRoot . DIRECTORY_SEPARATOR . $dir;
    if (!is_dir($dirPath)) {
        continue;
    }

    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dirPath));
    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $path = $file->getPathname();
            $relativePath = relativePath($projectRoot, $path);
            $content = file_get_contents($path);

            if ($content === false || trim($content) === '') {
                $errors[] = "PHP file is empty: {$relativePath}";
                continue;
            }

            // Check PHP opening tag
            $trimmed = ltrim($content);
            if (!str_starts_with($trimmed, '<?php') && !str_starts_with($trimmed, '#!/usr/bin/env php')) {
                $errors[] = "PHP file does not start with a valid PHP tag: {$relativePath}";
            }

            // Check config files contain return
            if ($dir === 'config') {
                if (!str_contains($content, 'return')) {
                    $errors[] = "Config file does not contain a return statement: {$relativePath}";
                }
            }
        }
    }
}

// Check root executable PHP files (e.g., intisari)
$intisariPath = $projectRoot . DIRECTORY_SEPARATOR . 'intisari';
if (is_file($intisariPath)) {
    $content = file_get_contents($intisariPath);
    if ($content !== false) {
        $trimmed = ltrim($content);
        if (!str_starts_with($trimmed, '#!/usr/bin/env php') && !str_starts_with($trimmed, '<?php')) {
            $errors[] = "Root executable 'intisari' must start with #!/usr/bin/env php or <?php";
        }
    } else {
        $errors[] = "Root executable 'intisari' is missing or unreadable.";
    }
}

// 2. Check phpunit.xml
$phpunitXml = $projectRoot . DIRECTORY_SEPARATOR . 'phpunit.xml';
if (is_file($phpunitXml)) {
    $relativePath = relativePath($projectRoot, $phpunitXml);
    libxml_use_internal_errors(true);
    $doc = new DOMDocument();
    if ($doc->load($phpunitXml) === false) {
        $errors[] = "phpunit.xml is not valid XML: " . implode(', ', array_map(fn($e) => trim($e->message), libxml_get_errors()));
    }
    libxml_clear_errors();
} else {
    $errors[] = "phpunit.xml is missing.";
}

// 3. Check .github/workflows/*.yml
$workflowsDir = $projectRoot . DIRECTORY_SEPARATOR . '.github' . DIRECTORY_SEPARATOR . 'workflows';
if (is_dir($workflowsDir)) {
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($workflowsDir));
    foreach ($iterator as $file) {
        if ($file->isFile() && in_array($file->getExtension(), ['yml', 'yaml'], true)) {
            $path = $file->getPathname();
            $relativePath = relativePath($projectRoot, $path);
            $content = file_get_contents($path);

            if ($content === false || trim($content) === '') {
                $errors[] = "Workflow file is empty: {$relativePath}";
                continue;
            }

            if (!str_contains($content, 'name:') || !str_contains($content, 'jobs:')) {
                $errors[] = "Workflow file must contain 'name:' and 'jobs:': {$relativePath}";
            }
        }
    }
}

// 4. Check Markdown files for collapsed content
$mdFiles = ['README.md', 'AGENTS.md', 'CONTRIBUTING.md', 'CHANGELOG.md'];
// Also check docs/**/*.md
$docsDir = $projectRoot . DIRECTORY_SEPARATOR . 'docs';
if (is_dir($docsDir)) {
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($docsDir));
    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'md') {
            $mdFiles[] = relativePath($projectRoot, $file->getPathname());
        }
    }
}

foreach ($mdFiles as $mdFile) {
    $path = $projectRoot . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $mdFile);
    if (!is_file($path)) {
        continue;
    }

    $content = file_get_contents($path);
    if ($content === false) {
        $errors[] = "Markdown file is unreadable: {$mdFile}";
        continue;
    }

    if (trim($content) === '') {
        continue;
    }

    $lines = preg_split('/\R/', $content) ?: [];
    if (count($lines) <= 1 && strlen($content) > 100) {
        $errors[] = "Markdown file appears to be collapsed into a single giant line: {$mdFile}";
    }
}

// 5. Check .env.example
$envExample = $projectRoot . DIRECTORY_SEPARATOR . '.env.example';
if (is_file($envExample)) {
    $relativePath = relativePath($projectRoot, $envExample);
    $content = file_get_contents($envExample);
    if ($content === false) {
        $errors[] = ".env.example is unreadable.";
    } else {
        $lines = preg_split('/\R/', $content) ?: [];
        if (count($lines) <= 2) {
            $errors[] = ".env.example must contain multiple lines.";
        }
        if (!str_contains($content, 'APP_NAME=')) {
            $errors[] = ".env.example must contain APP_NAME.";
        }
    }
} else {
    $errors[] = ".env.example is missing.";
}

// Output Results
if ($errors !== []) {
    fwrite(STDERR, "Source Integrity Check FAILED:\n");
    foreach ($errors as $error) {
        fwrite(STDERR, "- {$error}\n");
    }
    exit(1);
}

echo "Source Integrity Check PASSED.\n";
exit(0);

function relativePath(string $projectRoot, string $path): string
{
    $relative = substr($path, strlen($projectRoot) + 1);
    return str_replace(DIRECTORY_SEPARATOR, '/', $relative);
}
