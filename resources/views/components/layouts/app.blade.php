<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'POS Nasi Lawar Ulucatu' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

        * {
            font-family: 'Inter', sans-serif;
        }

        .sidebar-link {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-link:hover {
            transform: translateX(4px);
        }

        .card-shadow {
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px 0 rgba(0, 0, 0, 0.02);
        }

        .stats-card {
            transition: all 0.2s ease-in-out;
            border: 1px solid #e2e8f0;
        }

        .stats-card:hover {
            border-color: #cbd5e1;
            box-shadow: 0 4px 12px -2px rgba(0, 0, 0, 0.04);
        }

        .modal {
            display: none;
        }

        .modal.active {
            display: flex;
        }
    </style>

    @livewireStyles
</head>

<body>
    <main class="bg-slate-50 min-h-screen">
        {{ $slot }}
    </main>
    @livewireScripts
</body>

</html>