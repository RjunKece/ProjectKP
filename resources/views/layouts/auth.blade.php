<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Authentication') | ERP System</title>

    <!-- FAVICON -->
    <link rel="icon" href="{{ asset('images/logoPTGoldenIB.ico') }}">

    <!-- GOOGLE FONTS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- ASSETS -->
    @vite(['resources/css/app.css'])

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { width: 100%; height: 100%; overflow: hidden; }
    </style>
</head>

<body style="margin:0; padding:0; width:100vw; height:100vh; overflow:hidden;">

    @yield('content')

</body>
</html>
