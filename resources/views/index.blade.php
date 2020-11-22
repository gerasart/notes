<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Notes</title>
    <!-- Fonts -->
    <link href="/css/app.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

<div class="container">
    <div id="app">
        <h1>Notes</h1>
        <Notes></Notes>
    </div>
</div>

<footer>
    <script src="/js/app.js"></script>
</footer>
</body>
</html>
