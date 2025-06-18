<?php

// Auto-update Blade templates script

// Get all view files
$viewPath = __DIR__ . '/../resources/views';
$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($viewPath),
    RecursiveIteratorIterator::SELF_FIRST
);

// Skip these files that we already updated manually
$skipFiles = [
    'index.blade.php',
    'dashboard/userDashboard.blade.php',
    'dashboard/adminDashboard.blade.php',
    'task/create.blade.php',
    'group/edit.blade.php',
    'group/join_groups.blade.php',
    'layouts/app.blade.php',
    'partials/header.blade.php',
];

$headerPattern = '/<header.*?<\/header>/s';
$contentStartPattern = '/@section\(\'content\'\)(.*?)(?=<header|\<div|\<section|\<main|@include)/s';

// Loop through all blade files
foreach ($files as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $relativePath = str_replace($viewPath . '/', '', $file->getPathname());
        
        // Skip files we already updated
        if (in_array($relativePath, $skipFiles)) {
            continue;
        }
        
        // Check if it's a blade template
        if (strpos($file->getFilename(), '.blade.php') !== false) {
            $content = file_get_contents($file->getPathname());
            
            // Skip if this file doesn't extend layouts.app or doesn't have @section('content')
            if (strpos($content, "@extends('layouts.app')") === false || 
                strpos($content, "@section('content')") === false) {
                continue;
            }
            
            // Try to determine a title from the file content
            if (preg_match('/<h1[^>]*>(.*?)<\/h1>/s', $content, $matches)) {
                $title = strip_tags($matches[1]);
            } else {
                // Default title based on the file name
                $title = ucfirst(str_replace(['-', '_', '.blade.php'], [' ', ' ', ''], $file->getFilename()));
            }
            
            // Build the new header include
            $phpTitle = "<?php \$title = '$title'; ?>";
            $includeHeader = "@include('partials.header', ['title' => \$title])";
            
            // Replace old header with new standardized header
            $updatedContent = $content;
            
            // If there's a <header> tag, replace it
            if (preg_match($headerPattern, $content)) {
                $updatedContent = preg_replace($headerPattern, $phpTitle . "\n" . $includeHeader, $content);
            } 
            // Otherwise inject after @section('content')
            else if (preg_match($contentStartPattern, $content, $matches)) {
                $updatedContent = preg_replace(
                    $contentStartPattern, 
                    "@section('content')" . $matches[1] . $phpTitle . "\n" . $includeHeader . "\n<div class=\"container fade-section\">", 
                    $content
                );
            }
            
            // Ensure we have the closing div for container if we added one
            if ($updatedContent !== $content && strpos($updatedContent, '<div class="container fade-section">') !== false && 
                strpos($updatedContent, '</div>') === false) {
                $updatedContent .= "\n</div>";
            }
            
            // Save the updated file
            file_put_contents($file->getPathname(), $updatedContent);
            echo "Updated: " . $relativePath . "\n";
        }
    }
}
