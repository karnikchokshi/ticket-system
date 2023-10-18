<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }}</title>
</head>
<body>
    <div style="background-color: #f4f4f4; padding: 20px;">
        <div style="background-color: #fff; padding: 20px;">
            <h1>{{ config('app.name') }}</h1>
            <p>{{ $messages }}</p>
        </div>
    </div>
</body>
</html>
