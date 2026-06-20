<?php

declare(strict_types=1);

$projectRoot = realpath(dirname(__DIR__));
$docsDir = $projectRoot . DIRECTORY_SEPARATOR . 'docs';

$filesToCheck = [];
$filesToCheck[] = $projectRoot . DIRECTORY_SEPARATOR . 'README.md';

if (is_dir($docsDir)) {
    $directoryIterator = new RecursiveDirectoryIterator($docsDir);
    $iterator = new RecursiveIteratorIterator($directoryIterator);
    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'md') {
            $filesToCheck[] = $file->getRealPath();
        }
    }
}

$errors = [];

foreach ($filesToCheck as $file) {
    $content = file_get_contents($file);
    $filename = basename($file);
    $relativeFilePath = str_replace($projectRoot . DIRECTORY_SEPARATOR, '', $file);

    // Split the content by triple backticks (```)
    $parts = explode('```', $content);
    
    $evenParts = [];
    $oddParts = [];
    foreach ($parts as $index => $part) {
        if ($index % 2 === 0) {
            $evenParts[] = $part;
        } else {
            $oddParts[] = $part;
        }
    }

    // 1. Check exactly one H1 per file (only outside code blocks)
    $h1Count = 0;
    foreach ($evenParts as $part) {
        $partLines = explode("\n", str_replace("\r\n", "\n", $part));
        foreach ($partLines as $line) {
            if (str_starts_with(trim($line), '# ')) {
                $h1Count++;
            }
        }
    }
    if ($h1Count !== 1) {
        $errors[] = "[{$relativeFilePath}] Must contain exactly one H1 header. Found: {$h1Count}";
    }

    // 2. Check no empty code blocks (only inside code blocks)
    foreach ($oddParts as $part) {
        $partLines = explode("\n", str_replace("\r\n", "\n", $part));
        // Remove the first line which is the language identifier
        array_shift($partLines);
        $blockContent = implode("\n", $partLines);
        if (trim($blockContent) === '') {
            $errors[] = "[{$relativeFilePath}] Contains an empty code block.";
        }
    }

    // 3. Extract and check links (only outside code blocks)
    $outsideContent = implode(' ', $evenParts);
    preg_match_all('/(?<!\!)\[([^\]]+)\]\(([^)]+)\)/', $outsideContent, $matches);
    $links = $matches[2];

    foreach ($links as $link) {
        $anchorPos = strpos($link, '#');
        $cleanLink = $anchorPos !== false ? substr($link, 0, $anchorPos) : $link;
        if ($cleanLink === '') {
            continue;
        }

        if (str_starts_with($cleanLink, 'http://') || str_starts_with($cleanLink, 'https://') || str_starts_with($cleanLink, 'mailto:')) {
            continue;
        }

        $targetPath = '';
        if (str_starts_with($cleanLink, 'file:///')) {
            $decoded = rawurldecode($cleanLink);
            $pathPart = substr($decoded, 8); // strip file:///
            $pathPart = str_replace('/', DIRECTORY_SEPARATOR, $pathPart);
            
            $starterIndex = strpos($pathPart, 'intisari-starter');
            if ($starterIndex !== false) {
                $relativePath = substr($pathPart, $starterIndex + strlen('intisari-starter') + 1);
                $targetPath = $projectRoot . DIRECTORY_SEPARATOR . $relativePath;
            } else {
                $parentDir = dirname($projectRoot);
                $packages = ['http', 'console', 'intisari', 'validation', 'router', 'session', 'view', 'container', 'database', 'cache'];
                $foundPkg = false;
                foreach ($packages as $pkg) {
                    $pkgPos = strpos($pathPart, DIRECTORY_SEPARATOR . $pkg . DIRECTORY_SEPARATOR);
                    if ($pkgPos !== false) {
                        $siblingRepoDir = $parentDir . DIRECTORY_SEPARATOR . $pkg;
                        if (!is_dir($siblingRepoDir)) {
                            // Sibling repo is not available, skip validation
                            $foundPkg = true; 
                            break;
                        }
                        $relativePath = substr($pathPart, $pkgPos + 1);
                        $targetPath = $parentDir . DIRECTORY_SEPARATOR . $relativePath;
                        $foundPkg = true;
                        break;
                    }
                }
                
                if (!$foundPkg) {
                    if (file_exists($pathPart)) {
                        $targetPath = $pathPart;
                    } else {
                        continue;
                    }
                }
            }
        } else {
            $fileDir = dirname($file);
            $targetPath = realpath($fileDir . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $cleanLink));
            if ($targetPath === false) {
                $targetPath = $fileDir . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $cleanLink);
            }
        }

        if ($targetPath !== '' && !file_exists($targetPath)) {
            $errors[] = "[{$relativeFilePath}] Broken link: '{$link}' (Resolved target path not found: '{$targetPath}')";
        }
    }
}

if ($errors !== []) {
    echo "Documentation Quality Check FAILED:\n";
    foreach ($errors as $error) {
        echo "- {$error}\n";
    }
    exit(1);
}

echo "Documentation Quality Check PASSED.\n";
exit(0);
