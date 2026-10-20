<nav x-data="{ open: false }" class="bg-blue-600 text-white">
    <div class="max-w-6xl mx-auto px-6">
        <div class="flex justify-between h-16 items-center">

            {{-- Logo / nama --}}
            <a href="{{ route('home') }}" class="font-bold text-xl">KosSinga</a>

            {{-- Menu desktop --}}
            <div class="hidden sm:flex gap-6 items-center">
                <a href="{{ route('home') }}"
                   class="{{ request()->routeIs('home') ? 'font-bold underline' : '' }}">Beranda</a>
                <a href="{{ route('kos.index') }}"
                   class="{{ request()->routeIs('kos.*') ? 'font-bold underline' : '' }}">Cari Kos</a>
                <a href="{{ route('favorit') }}"
                   class="{{ request()->routeIs('favorit') ? 'font-bold underline' : '' }}">Favorit</a>

                {{-- Conditional rendering: menu sesuai status login --}}
                @auth
                    <a href="{{ route('dashboard') }}">Admin</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="{{ request()->routeIs('login') ? 'font-bold underline' : '' }}">Login</a>
                @endauth
            </div>

            {{-- Tombol hamburger (HP) --}}
            <button @click="open = ! open" class="sm:hidden text-2xl focus:outline-none">☰</button>
        </div>
    </div>

    {{-- Menu HP --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden px-6 pb-4 space-y-2">
        <a href="{{ route('home') }}" class="block">Beranda</a>
        <a href="{{ route('kos.index') }}" class="block">Cari Kos</a>
        <a href="{{ route('favorit') }}" class="block">Favorit</a>

        @auth
            <a href="{{ route('dashboard') }}" class="block">Admin</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Logout</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="block">Login</a>
        @endauth
    </div>
</nav>