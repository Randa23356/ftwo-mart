<?php

$directory = __DIR__ . '/resources/views';
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
$bladeFiles = new RegexIterator($files, '/\.blade\.php$/');

$directives = [
    'if' => 'endif',
    'foreach' => 'endforeach',
    'forelse' => 'endforelse',
    'auth' => 'endauth',
    'guest' => 'endguest',
    'push' => 'endpush',
    'prepend' => 'endprepend',
    'section' => ['endsection', 'stop', 'show'],
    'can' => 'endcan',
    'cannot' => 'endcannot',
    'error' => 'enderror',
    'switch' => 'endswitch',
    'while' => 'endwhile',
    'unless' => 'endunless',
    'isset' => 'endisset',
    // 'empty' => 'endempty', // Commented out to avoid false positives with @forelse
];

echo "Scanning Blade files in $directory...\n";
echo "Excluding: resources/views/layouts/\n\n";

$errorCount = 0;

foreach ($bladeFiles as $file) {
    // 1. Scan excluded directories
    if (strpos($file->getPathname(), 'vendor') !== false || 
        strpos($file->getPathname(), 'storage') !== false ||
        strpos($file->getPathname(), 'resources/views/layouts') !== false) {
        continue;
    }

    $content = file_get_contents($file->getPathname());
    
    // Remove comments
    $cleanContent = preg_replace('/\{\{--.*?--\}\}/s', '', $content);

    // 2. Check for mismatched braces {{ }} and {!! !!}
    $echoOpen = substr_count($cleanContent, '{{');
    $echoClose = substr_count($cleanContent, '}}');
    if ($echoOpen !== $echoClose) {
        echo "WARNING in " . $file->getPathname() . ": Mismatched echo braces {{ }}. Opened: $echoOpen, Closed: $echoClose\n";
        $errorCount++;
    }

    $rawOpen = substr_count($cleanContent, '{!!');
    $rawClose = substr_count($cleanContent, '!!}');
    if ($rawOpen !== $rawClose) {
        echo "WARNING in " . $file->getPathname() . ": Mismatched raw echo braces {!! !!}. Opened: $rawOpen, Closed: $rawClose\n";
        $errorCount++;
    }

    // 3. Check for suspicious unmatched quotes (Basic Check)
    // We check line by line for odd number of quotes
    $lines = explode("\n", $cleanContent);
    foreach ($lines as $lineNum => $line) {
        // Remove escaped quotes
        $lineClean = str_replace(['\"', "\'"], '', $line);
        if (substr_count($lineClean, '"') % 2 !== 0) {
            // Very noisy, enable only if strict
            // echo "WARNING in " . $file->getPathname() . ": Potential unclosed double quote (\") at line " . ($lineNum + 1) . "\n";
        }
        if (substr_count($lineClean, "'") % 2 !== 0) {
            // Very noisy
            // echo "WARNING in " . $file->getPathname() . ": Potential unclosed single quote (') at line " . ($lineNum + 1) . "\n";
        }
    }

    // 4. HTML Tag Balance Check
    $htmlContent = preg_replace('/\{\{.*?\}\}/s', '', $cleanContent);
    $htmlContent = preg_replace('/\{!!.*?!!\}/s', '', $htmlContent);
    $htmlContent = preg_replace('/<\?php.*?\?>/s', '', $htmlContent);

    $tagsToCheck = ['div', 'main', 'section', 'footer'];
    foreach ($tagsToCheck as $tag) {
        $openCount = preg_match_all("/<$tag(\s|>)/i", $htmlContent);
        $closeCount = preg_match_all("/<\/$tag>/i", $htmlContent);
        
        if ($openCount !== $closeCount) {
             echo "WARNING in " . $file->getPathname() . ": Unbalanced <$tag> tags. Opened: $openCount, Closed: $closeCount\n";
             $errorCount++;
        }
    }

    // 5. Directive Check
    $stack = [];
    $hasError = false;
    
    foreach ($lines as $lineNum => $line) {
        preg_match_all('/@(\w+)/', $line, $matches);
        
        foreach ($matches[1] as $match) {
            $directive = $match;
            
            if (isset($directives[$directive])) {
                $stack[] = ['directive' => $directive, 'line' => $lineNum + 1];
            } else {
                foreach ($directives as $start => $ends) {
                    if (is_array($ends)) {
                        if (in_array($directive, $ends)) {
                            if (!empty($stack)) {
                                $last = array_pop($stack);
                                if ($last['directive'] !== $start) {
                                    if ($start === 'section' && in_array($directive, ['stop', 'endsection', 'show'])) {
                                        // OK
                                    } else {
                                        // Mismatch warning
                                    }
                                }
                            }
                        }
                    } else {
                        if ($directive === $ends) {
                            if (!empty($stack)) {
                                $last = array_pop($stack);
                            }
                        }
                    }
                }
            }
        }
    }

    if (!empty($stack)) {
        echo "ERROR in " . $file->getPathname() . ": Unclosed directives:\n";
        foreach ($stack as $item) {
            echo "  - @" . $item['directive'] . " started at line " . $item['line'] . "\n";
        }
        $errorCount++;
    }
}

if ($errorCount === 0) {
    echo "Scan complete. No obvious issues found.\n";
} else {
    echo "\nFound potential issues in $errorCount files.\n";
}
