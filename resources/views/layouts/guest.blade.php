<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Auth - GlowUp Skincare</title>
    <link href="https://fonts.bunny.net/css?family=playfair+display:700|figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .font-playfair { font-family: 'Playfair Display', serif; }
        .bg-auth {
            background-image: linear-gradient(rgba(255, 255, 255, 0.8), rgba(255, 255, 255, 0.8)),
                              url("{{ asset('storage/img/background.jpg') }}");
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-auth">
        <div>
            <a href="/">
                <h1 class="text-4xl font-playfair font-bold text-pink-600 tracking-tighter">GLOWUP</h1>
            </a>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-8 py-10 bg-white/70 backdrop-blur-lg shadow-2xl overflow-hidden sm:rounded-3xl border border-white/50">
            {{ $slot }}
        </div>
    </div>
</body>
</html>
