<?php

declare(strict_types=1);

$projectRoot = realpath(dirname(__DIR__));

if ($projectRoot === false) {
    fwrite(STDERR, "Unable to resolve project root.\n");
    exit(1);
}

$excludedDirectories = ['.git', 'node_modules', 'vendor'];
$directory = new RecursiveDirectoryIterator($projectRoot, FilesystemIterator::SKIP_DOTS);
$filter = new RecursiveCallbackFilterIterator(
    $directory,
    static function (SplFileInfo $item) use ($excludedDirectories): bool {
        return !$item->isDir() || !in_array($item->getFilename(), $excludedDirectories, true);
    }
);

$files = [];
$iterator = new RecursiveIteratorIterator($filter);
foreach ($iterator as $file) {
    if ($file->isFile() && strtolower($file->getExtension()) === 'md') {
        $files[] = $file->getPathname();
    }
}
sort($files);

$errors = [];
$forbiddenClaims = [
    '/\b(?:supports|provides|includes|implements|offers)\s+(?:an?\s+)?(?:built-in\s+)?(?:authentication|authorization|csrf|orm|queues?|scheduler|mail)\b/i',
    '/\b(?:laravel|codeigniter|artisan)[- ]compatible\b/i',
    '/^\s*php\s+intisari\s+(?:make:model|make:migration|migrate|db:seed|queue:\S+|schedule:\S+)\b/i',
];

foreach ($files as $file) {
    $content = file_get_contents($file);
    if ($content === false) {
        $errors[] = sprintf('[%s] Unable to read file.', relativePath($projectRoot, $file));
        continue;
    }

    $relativePath = relativePath($projectRoot, $file);
    $lines = preg_split('/\R/', $content) ?: [];
    $outsideLines = [];
    $h1Count = 0;
    $fenceCharacter = null;
    $fenceLength = 0;
    $fenceLine = 0;
    $codeLines = [];

    foreach ($lines as $index => $line) {
        $lineNumber = $index + 1;

        if (preg_match('/^\s*(`{3,}|~{3,})(.*)$/', $line, $matches) === 1) {
            $marker = $matches[1];
            $character = $marker[0];

            if ($fenceCharacter === null) {
                $language = trim($matches[2]);
                if ($language === '') {
                    $errors[] = sprintf('[%s:%d] Code fence must have a language identifier.', $relativePath, $lineNumber);
                }

                $fenceCharacter = $character;
                $fenceLength = strlen($marker);
                $fenceLine = $lineNumber;
                $codeLines = [];
                continue;
            }

            if ($character === $fenceCharacter && strlen($marker) >= $fenceLength && trim($matches[2]) === '') {
                if (trim(implode("\n", $codeLines)) === '') {
                    $errors[] = sprintf('[%s:%d] Code block must not be empty.', $relativePath, $fenceLine);
                }

                $fenceCharacter = null;
                $fenceLength = 0;
                $fenceLine = 0;
                $codeLines = [];
                continue;
            }
        }

        if ($fenceCharacter !== null) {
            $codeLines[] = $line;
        } else {
            $outsideLines[] = $line;
            if (preg_match('/^#\s+\S/', $line) === 1) {
                $h1Count++;
            }
        }

        if (preg_match('/\b(?:core-dependent|planned)\b/i', $line) !== 1) {
            foreach ($forbiddenClaims as $pattern) {
                if (preg_match($pattern, $line) === 1) {
                    $errors[] = sprintf('[%s:%d] Unsupported claim must be removed or marked core-dependent/planned.', $relativePath, $lineNumber);
                    break;
                }
            }
        }
    }

    if ($fenceCharacter !== null) {
        $errors[] = sprintf('[%s:%d] Code fence is not closed.', $relativePath, $fenceLine);
    }

    if ($h1Count !== 1) {
        $errors[] = sprintf('[%s] Must contain exactly one H1 heading. Found: %d.', $relativePath, $h1Count);
    }

    $outsideContent = implode("\n", $outsideLines);
    preg_match_all('/!?\[[^\]]*\]\(([^)]+)\)/', $outsideContent, $linkMatches);

    foreach ($linkMatches[1] as $link) {
        validateRelativeLink($projectRoot, $file, trim($link), $relativePath, $errors);
    }
}

if ($errors !== []) {
    echo "Documentation Quality Check FAILED:\n";
    foreach ($errors as $error) {
        echo "- {$error}\n";
    }
    exit(1);
}

printf("Documentation Quality Check PASSED (%d Markdown files).\n", count($files));
exit(0);

function relativePath(string $projectRoot, string $path): string
{
    $relative = substr($path, strlen($projectRoot) + 1);

    return str_replace(DIRECTORY_SEPARATOR, '/', $relative);
}

/**
 * @param list<string> $errors
 */
function validateRelativeLink(
    string $projectRoot,
    string $sourceFile,
    string $link,
    string $relativeSource,
    array &$errors,
): void {
    if ($link === '' || str_starts_with($link, '#')) {
        return;
    }

    if (preg_match('/^(?:https?:|mailto:)/i', $link) === 1) {
        return;
    }

    if (preg_match('~^(?:file:|[a-z]:[\\\\/]|[\\\\/]{2})~i', $link) === 1) {
        $errors[] = sprintf('[%s] Link must be relative: %s', $relativeSource, $link);
        return;
    }

    $target = rawurldecode(explode('#', $link, 2)[0]);
    $targetPath = dirname($sourceFile) . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $target);

    if (!file_exists($targetPath)) {
        $errors[] = sprintf('[%s] Broken relative link: %s', $relativeSource, $link);
        return;
    }

    $realTarget = realpath($targetPath);
    if ($realTarget !== false && !str_starts_with($realTarget, $projectRoot . DIRECTORY_SEPARATOR) && $realTarget !== $projectRoot) {
        $errors[] = sprintf('[%s] Relative link leaves the project: %s', $relativeSource, $link);
    }
}
