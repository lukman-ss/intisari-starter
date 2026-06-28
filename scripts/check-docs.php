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
$docsToProcess = [];

$rootFiles = ['README.md', 'AGENTS.md', 'CONTRIBUTING.md', 'CHANGELOG.md'];
foreach ($rootFiles as $f) {
    if (is_file($basePath . DIRECTORY_SEPARATOR . $f)) {
        $docsToProcess[] = $basePath . DIRECTORY_SEPARATOR . $f;
    }
}

$docsDir = $basePath . DIRECTORY_SEPARATOR . 'docs';
if (is_dir($docsDir)) {
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($docsDir));
    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'md') {
            $docsToProcess[] = $file->getPathname();
        }
    }
}

foreach ($docsToProcess as $docPath) {
    $content = file_get_contents($docPath);
    if ($content === false) {
        fail("Could not read file: " . $docPath);
    }
    
    $lines = explode("\n", str_replace("\r", "", $content));
    
    $h1Count = 0;
    foreach ($lines as $line) {
        if (preg_match('/^#\s+[^#]/', $line)) {
            $h1Count++;
        }
    }
    if (trim($content) !== '' && $h1Count !== 1) {
        fail("Expected exactly one H1 in " . basename($docPath) . ", found $h1Count");
    }
    
    $inCodeBlock = false;
    $codeBlockLang = '';
    $codeBlockLines = 0;
    foreach ($lines as $i => $line) {
        if (str_starts_with(trim($line), '```')) {
            if (!$inCodeBlock) {
                $inCodeBlock = true;
                $codeBlockLang = trim(substr(trim($line), 3));
                $codeBlockLines = 0;
                
                if ($codeBlockLang === '') {
                    fail("Code fence without language identifier at line " . ($i + 1) . " in " . basename($docPath));
                }
            } else {
                $inCodeBlock = false;
                
                if ($codeBlockLines === 0) {
                    fail("Empty code fence ending at line " . ($i + 1) . " in " . basename($docPath));
                }
            }
        } elseif ($inCodeBlock) {
            $codeBlockLines++;
        }
    }
    
    if ($inCodeBlock) {
        fail("Unbalanced code fences in " . basename($docPath));
    }
    
    if (preg_match_all('/\[([^\]]+)\]\(([^)]+)\)/', $content, $matches)) {
        foreach ($matches[2] as $link) {
            $url = trim($link);
            
            $parts = explode('#', $url, 2);
            $path = $parts[0];
            
            if ($path !== '' && !preg_match('/^(http|https|mailto):/', $path)) {
                if (str_starts_with($path, 'file:///')) {
                    fail("Contains file:/// link ($path) in " . basename($docPath));
                }
                
                if (preg_match('/^[a-zA-Z]:\\\\|^\\/Users\\//', $path)) {
                    fail("Contains local machine path ($path) in " . basename($docPath));
                }
                
                if (str_ends_with($path, '.md') && !str_starts_with($path, 'http')) {
                    $targetPath = dirname($docPath) . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $path);
                    if (!is_file($targetPath)) {
                        fail("Broken internal relative link ($path) in " . basename($docPath));
                    }
                }
            }
        }
    }
    
    $invalidPatterns = ['file:///', 'C:\\', 'D:\\', '/Users/'];
    foreach ($invalidPatterns as $pattern) {
        if (stripos(str_replace('\\', '/', $content), str_replace('\\', '/', $pattern)) !== false) {
            fail("Documentation contains invalid local path/string ($pattern) in " . basename($docPath));
        }
    }
}

pass("Docs Integrity Check PASSED.");
exit(0);
