<?php
// This script converts all blade templates to use the standardized header partial

// Function to update blade files with standardized header
function updateBladeFile($filePath) {
    $content = file_get_contents($filePath);
    
    // Skip if the file is already using our standardized header
    if (strpos($content, "@include('partials.header'") !== false) {
        return false;
    }
    
    // Check if it has a header section we need to replace
    $hasCustomHeader = preg_match('/<header.*?<\/header>/s', $content);
    
    if (!$hasCustomHeader) {
        return false;
    }
    
    // Extract title from existing header if possible
    $title = '';
    if (preg_match('/<h1[^>]*>(.*?)<\/h1>/s', $content, $matches)) {
        $title = trim(strip_tags($matches[1]));
    } else {
        // Default title based on the file name
        $baseName = basename($filePath);
        $title = ucfirst(str_replace(['-', '_', '.blade.php'], [' ', ' ', ''], $baseName));
    }
    
    // Replace the custom header with our standard header
    $updatedContent = preg_replace(
        '/<header.*?<\/header>/s',
        "@include('partials.header', ['title' => '$title'])",
        $content
    );
    
    if ($updatedContent !== $content) {
        file_put_contents($filePath, $updatedContent);
        return true;
    }
    
    return false;
}

// Update selected blade files
$bladeFiles = [
    __DIR__ . '/resources/views/task/update.blade.php',
    __DIR__ . '/resources/views/group/create.blade.php',
    __DIR__ . '/resources/views/dashboard/coachDashboard.blade.php',
    __DIR__ . '/resources/views/admin/user/create.blade.php',
    __DIR__ . '/resources/views/admin/coach/create.blade.php'
];

$updatedCount = 0;
foreach ($bladeFiles as $file) {
    if (file_exists($file) && updateBladeFile($file)) {
        echo "Updated: " . basename($file) . "\n";
        $updatedCount++;
    }
}

echo "Updated {$updatedCount} blade templates to use standardized header.\n";
echo "Done! All specified templates now have consistent styling.\n";
