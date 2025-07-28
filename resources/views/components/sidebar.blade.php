<div class="w-64 h-screen bg-gradient-to-b from-[#4361ee] to-[#3a0ca3] text-white fixed top-0 left-0 shadow-lg flex flex-col">

    <!-- Logo -->
    <div class="flex items-center gap-2 px-5 py-3 border-b border-white/20">
        <img src="{{ asset('images/logo.png') }}" class="w-10 h-10 rounded-full border-2 border-white object-cover">
        <h2 class="text-sm font-semibold">MediAssist</h2>
    </div>

    <!-- User Profile -->
    @auth
    <div class="flex items-center px-5 py-3 border-b border-white/20">
        <img src="{{ auth()->user()->profile_picture ?? asset('images/default-medcin.png') }}" class="w-10 h-10 rounded-full border-2 border-white object-cover">
        <div class="ml-2">
            <h4 class="text-xs font-semibold">
                {{ auth()->user()->name ?? 'Utilisateur' }}
            </h4>
            <span class="text-[10px] opacity-80">
                {{ auth()->user()->role === 'admin' ? 'Médecin' : 'Assistante' }}
            </span>
        </div>
    </div>
    @endauth

    <!-- Navigation Links -->
    <ul class="flex flex-col p-3 space-y-1">
        <x-slidbaritem logo="fas fa-user-md" href="/medecin" :active="request()->is('medecin')">Tableau De Bord</x-slidbaritem>
        <x-slidbaritem logo="fas fa-calendar-alt" href="/" :active="request()->is('/')">Calendrier</x-slidbaritem>
        <x-slidbaritem logo="fas fa-users" href="/patients" :active="request()->is('patients')">Patients</x-slidbaritem>
        <x-slidbaritem logo="fas fa-pills" href="/medicaments" :active="request()->is('medicaments')">Médicaments</x-slidbaritem>
        <x-slidbaritem logo="fas fa-flask" href="/analyses" :active="request()->is('analyses')">Analyses</x-slidbaritem>
        <x-slidbaritem logo="fas fa-chart-line" href="/rapports" :active="request()->is('rapports')">Rapports</x-slidbaritem>
    </ul>

    <!-- Logout -->
    <div class="mt-auto px-5 py-3 border-t border-white/20">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center gap-2 text-white opacity-80 hover:opacity-100 transition">
                <i class="fas fa-sign-out-alt"></i> Déconnexion
            </button>
        </form>
    </div>
</div>
