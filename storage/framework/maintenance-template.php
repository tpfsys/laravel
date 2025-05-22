<?php

use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Foundation\Exceptions\RegisterErrorViewPaths;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

// Default values
$data = [
    'time' => time(),
    'message' => 'The application is currently in maintenance mode. Please check back soon.',
    'retry' => 60,
];

// Check for the down file which contains our payload
if (file_exists($down = __DIR__.'/down')) {
    $downFileData = json_decode(file_get_contents($down), true);
    
    // Merge with default data, preserving custom settings
    $data = array_merge($data, $downFileData);
}

// Set HTTP headers
header('Content-Type: text/html; charset=UTF-8');
header('HTTP/1.1 503 Service Unavailable');
header('Retry-After: '.$data['retry']);

// Display the view if it exists
if (file_exists(__DIR__.'/../../resources/views/errors/503.blade.php')) {
    (new RegisterErrorViewPaths)();
    
    // Parse variables needed for the view
    $message = $data['message'];
    $retryAfter = $data['retry'] ?? null;
    
    $content = View::make('errors.503', [
        'message' => $message,
        'retryAfter' => $retryAfter,
    ])->render();
    
    exit($content);
}

// Fallback to plain text
echo $data['message'];
exit(1);