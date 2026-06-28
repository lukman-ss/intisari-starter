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

$files = [];
foreach (['README.md', 'AGENTS.md', 'CONTRIBUTING.md', 'CHANGELOG.md'] as $f) {
    $path = $basePath . DIRECTORY_SEPARATOR . $f;
    if (is_file($path)) $files[] = $path;
}

$docsDir = $basePath . DIRECTORY_SEPARATOR . 'docs';
if (is_dir($docsDir)) {
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($docsDir));
    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'md') {
            $files[] = $file->getPathname();
        }
    }
}

foreach ($files as $file) {
    if (!is_readable($file)) {
        fail("File not readable: $file");
    }

    $content = file_get_contents($file);
    if (trim($content) === '') continue;

    // Code fences are balanced
    preg_match_all('/^\s*```/m', $content, $matches);
    if (count($matches[0]) % 2 !== 0) {
        fail("Unbalanced code fences in $file");
    }

    // Fenced code blocks language & empty
    if (preg_match_all('/^\s*```(.*?)\r?\n([\s\S]*?)^\s*```/m', $content, $matches)) {
        foreach ($matches[1] as $lang) {
            if (trim($lang) === '') fail("Code block missing language identifier in $file");
        }
        foreach ($matches[2] as $code) {
            if (trim($code) === '') fail("Empty code block in $file");
        }
    }

    // Exactly one H1
    $stripped = preg_replace('/^\s*```[\s\S]*?^\s*```/m', '', $content);
    preg_match_all('/^# /m', $stripped, $matches);
    if (count($matches[0]) !== 1) {
        fail("File must have exactly one H1 (found " . count($matches[0]) . "): $file");
    }

    // Broken internal links
    if (preg_match_all('/\[[^\]]*\]\(([^)]+)\)/', $stripped, $matches)) {
        foreach ($matches[1] as $link) {
            $link = trim($link);
            if ($link === '') continue;
            if (preg_match('/^(http|https|mailto|ftp):/i', $link) || str_starts_with($link, '#')) continue;
            
            $path = explode('#', $link)[0];
            if ($path !== '') {
                $path = urldecode($path);
                $fullPath = dirname($file) . DIRECTORY_SEPARATOR . $path;
                
                if (!file_exists($fullPath)) {
                    if (str_starts_with($path, '/')) {
                        $fullPath = $basePath . $path;
                    }
                    if (!file_exists($fullPath)) {
                        fail("Broken internal link '$link' in $file");
                    }
                }
            }
        }
    }

    // Forbidden strings
    $forbidden = ['file:///', 'C:\\', 'D:\\', '/Users/'];
    foreach ($forbidden as $f) {
        if (stripos($content, $f) !== false) fail("Docs contain forbidden string $f in $file");
    }
}

pass("Docs check verified.");
