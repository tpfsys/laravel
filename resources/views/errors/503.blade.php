<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>メンテナンス中 | Maintenance</title>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            width: 100%;
        }
        body {
            background-color: #fff;
            color: #333;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            font-size: 16px;
            line-height: 1.5;
        }
        .container {
            max-width: 600px;
            padding: 2rem;
            text-align: center;
        }
        h1 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        p {
            margin-bottom: 1rem;
        }
        .retry {
            margin-top: 2rem;
            font-size: 0.875rem;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>メンテナンス中 | Maintenance</h1>
        <p>{{ $message ?? 'The application is currently in maintenance mode. Please check back soon.' }}</p>
        
        @if($retryAfter)
        <div class="retry">
            The site will be available in {{ $retryAfter }} seconds.
        </div>
        @endif
    </div>
</body>
</html>