<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Authentication') | ERP System</title>

    <!-- FAVICON -->
    <link rel="icon" href="{{ asset('images/logoPTGoldenIB.ico') }}">

    <!-- ASSETS -->
    @vite(['resources/css/app.css'])
</head>

<body class="min-h-screen bg-[#fafafa]">

    {{-- WRAPPER AUTH --}}
    <div class="min-h-screen flex items-center justify-center px-4">

        {{-- CONTENT (LOGIN / AUTH FORM) --}}
        <div class="w-full max-w-md">
            @yield('content')
        </div>

    </div>

</body>
</html>
