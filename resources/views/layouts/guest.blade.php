<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body { font-family: 'Be Vietnam Pro', sans-serif; }
        </style>
    </head>
    <body class="bg-slate-950 text-slate-100 antialiased selection:bg-rose-500 selection:text-white">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 px-4">
            <!-- Brand Logo Header -->
            <div class="mb-6 flex items-center gap-3">
                <a href="/" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-rose-600 to-orange-500 flex items-center justify-center shadow-lg shadow-rose-500/20 font-bold text-white text-base group-hover:scale-105 transition-transform duration-200">
                        E
                    </div>
                    <div>
                        <div class="font-bold text-base tracking-wide text-white">E-COMMERCE SYSTEM</div>
                        <div class="text-xs text-slate-400">Admin & Store Authentication</div>
                    </div>
                </a>
            </div>

            <!-- Card Box -->
            <div class="w-full sm:max-w-md p-8 bg-slate-900/90 border border-slate-800 shadow-2xl rounded-2xl backdrop-blur-md">
                {{ $slot }}
            </div>

            <!-- Footer info -->
            <div class="mt-6 text-xs text-slate-500 font-mono">
                Laravel v{{ app()->version() }} • Docker Sail
            </div>
        </div>
    </body>
</html>