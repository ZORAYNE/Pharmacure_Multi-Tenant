<!DOCTYPE html>
<html lang="en">
<head>
    <title>Store Product - PHARMACURE</title>
    <meta charset="utf-8" />
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; max-width: 600px; }
        .message { padding: 1rem; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 4px; }
        a { display: inline-block; margin-top: 1rem; color: #007bff; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="message">
        Product has been successfully stored.
    </div>
    <a href="{{ route('tenant.pos.dashboard', ['tenant' => request()->route('tenant')]) }}">Back to Dashboard</a>
</body>
</html>
