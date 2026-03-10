<nav class="bg-slate-900 text-white p-4 flex justify-between items-center shadow-md sticky top-0 z-50">
    <div class="font-bold text-xl tracking-wide">
        POS Nasi Lawar Ulucatu
    </div>

    <div class="flex items-center gap-6">
        <div class="text-sm text-slate-300 text-right">
            <p class="font-semibold text-white">{{ auth()->user()->name ?? 'Guest' }}</p>
            <p class="text-xs">{{ ucfirst(auth()->user()->role ?? '') }}</p>
        </div>

        @auth
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded-lg text-sm font-semibold transition-colors flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Keluar
                </button>
            </form>
        @endauth
    </div>
</nav>