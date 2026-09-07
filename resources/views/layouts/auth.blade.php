<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0f172a">
    <title>@yield('title', 'Login') — Solcon Industries</title>

    <!-- PWA Primary Meta & App Icons -->
    <link rel="manifest" href="/manifest.json?v=2">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Solcon">
    <meta name="application-name" content="Solcon">
    <link rel="icon" type="image/png" sizes="192x192" href="/icons/icon-192x192.png?v=2">
    <link rel="icon" type="image/png" sizes="96x96" href="/icons/icon-96x96.png?v=2">
    <link rel="icon" type="image/png" sizes="48x48" href="/icons/icon-48x48.png?v=2">
    <link rel="icon" type="image/png" sizes="32x32" href="/icons/icon-32x32.png?v=2">
    <link rel="shortcut icon" href="/favicon.ico?v=2">
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png?v=2">
    <link rel="apple-touch-icon" sizes="152x152" href="/icons/icon-152x152.png?v=2">
    <link rel="apple-touch-icon" sizes="180x180" href="/icons/icon-180x180.png?v=2">
    <link rel="apple-touch-icon" sizes="167x167" href="/icons/icon-167x167.png?v=2">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-gradient-to-br from-slate-100 via-blue-50 to-slate-100 antialiased">
    @yield('content')
    @include('partials.pwa')
</body>
</html>
