<?php
$content = file_get_contents('d:/PHP PACKAGIST/intisari-starter/docs/security/index.md');
if (preg_match_all('/(```[a-z]*\s*```)/s', $content, $matches, PREG_OFFSET_CAPTURE)) {
    foreach ($matches[0] as $match) {
        $offset = $match[1];
        $matchedText = $match[0];
        $lineNum = substr_count(substr($content, 0, $offset), "\n") + 1;
        echo "Matched line $lineNum: " . var_export($matchedText, true) . "\n";
    }
} else {
    echo "No match found.\n";
}
