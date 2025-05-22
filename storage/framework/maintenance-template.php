<?php

use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Foundation\Exceptions\RegisterErrorViewPaths;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

$data = [
    'time' => time(),
    'message' => null,
];

if (file_exists($down = __DIR__.'/down')) {
    $data = json_decode(file_get_contents($down), true);
}

header('Content-Type: text/html; charset=UTF-8');
header('HTTP/1.1 503 Service Unavailable');
header('Retry-After: '.($data['retry'] ?? 60));

if (isset($data['message'])) {
    $message = $data['message'];
} else {
    $message = 'The application is currently in maintenance mode. Please check back soon.';
}

if (file_exists(__DIR__.'/../../resources/views/errors/503.blade.php')) {
    (new RegisterErrorViewPaths)();
    
    // Parse variables needed for the view
    $retryAfter = $data['retry'] ?? null;
    
    $content = View::make('errors.503', [
        'message' => $message,
        'retryAfter' => $retryAfter,
    ])->render();
    
    exit($content);
}

// Fallback plain text
echo $message;
exit(1);