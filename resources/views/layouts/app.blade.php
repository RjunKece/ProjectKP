<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'ERP System')</title>

    <link rel="icon" href="{{ asset('images/logoPTGoldenIB.ico') }}">

    @vite(['resources/css/app.css'])
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-gradient-to-br from-[#020617] via-[#020617] to-[#020617] text-slate-200">
    @yield('body')
</body>
</html>
