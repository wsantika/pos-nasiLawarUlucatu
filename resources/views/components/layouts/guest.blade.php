<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Login - Warung Madura POS' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
    </style>
    @livewireStyles
</head>

<body class="bg-slate-50 antialiased h-full flex items-center justify-center mb-50">
    {{ $slot }}

    @livewireScripts
</body>

</html>