<div class="w-64 h-screen bg-gradient-to-b from-[#4361ee] to-[#3a0ca3] text-white fixed top-0 left-0 shadow-lg flex flex-col">

    <!-- Logo -->
    <div class="flex items-center gap-2 px-5 py-3 border-b border-white/20">
        <i class="fas fa-hospital-user text-xl"></i>
        <h2 class="text-sm font-semibold">Clinic Propre</h2>
    </div>

    <!-- User Profile -->
    <div class="flex items-center px-5 py-3 border-b border-white/20">
        <img src="https://randomuser.me/api/portraits/men/1.jpg" class="w-10 h-10 rounded-full border-2 border-white object-cover">
        <div class="ml-2">
            <h4 class="text-xs font-semibold">Dr. Smith</h4>
            <span class="text-[10px] opacity-80">Physician</span>
        </div>
    </div>

    <!-- Navigation Links -->
    <ul class="flex flex-col p-3 space-y-1">

        <x-slidbaritem logo="fas fa-user-md" href="/" :active="request()->is('/')">Tableau Médecin</x-slidbaritem>
        <x-slidbaritem logo="fas fa-users" href="/patients" :active="request()->is('patients')">Patients</x-slidbaritem>
        <x-slidbaritem logo="fas fa-pills" href="/medicaments" :active="request()->is('medicaments')">Médicaments</x-slidbaritem>
        <x-slidbaritem logo="fas fa-flask" href="/analyses" :active="request()->is('analyses')">Analyses</x-slidbaritem>
        <x-slidbaritem logo="fas fa-chart-line" href="/rapports" :active="request()->is('rapports')">Rapports</x-slidbaritem>
        <x-slidbaritem logo="fas fa-cog" href="/settings" :active="request()->is('settings')">Paramètres</x-slidbaritem>
    </ul>

    <!-- Logout -->
    <div class="mt-auto px-5 py-3 border-t border-white/20">
        <a href="#" class="flex items-center gap-2 text-white opacity-80 hover:opacity-100 transition"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
    </div>
</div>
