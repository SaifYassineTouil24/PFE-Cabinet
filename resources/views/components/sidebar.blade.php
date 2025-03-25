<div class="w-64 h-screen bg-gradient-to-r from-blue-600 to-blue-600 text-white fixed top-0 left-0 shadow-lg flex flex-col">
    <!-- Logo -->
    <div class="flex items-center gap-2 px-6 py-4 border-b border-white/20">
        <i class="fas fa-hospital-user text-2xl"></i>
        <h2 class="text-lg font-semibold">Clinic Propre</h2>
    </div>

    <!-- User Profile -->
    <div class="flex items-center px-6 py-4 border-b border-white/20">
        <img src="https://randomuser.me/api/portraits/men/1.jpg" class="w-12 h-12 rounded-full border-2 border-white object-cover">
        <div class="ml-3">
            <h4 class="text-sm font-semibold">Dr. Smith</h4>
            <span class="text-xs opacity-80">Physician</span>
        </div>
    </div>

    <!-- Navigation Links -->
    <ul class="flex flex-col p-4 space-y-2">

        <x-slidbaritem logo="fas fa-user-md" href="/" :active="request()->is('/')">Tableau Médecin</x-slidbaritem>
        <x-slidbaritem logo="fas fa-users" href="/" :active="request()->is('/patients')">Patients</x-slidbaritem>
        <x-slidbaritem logo="fas fa-pills" href="/" :active="request()->is('/medicament')">Médicaments</x-slidbaritem>
        <x-slidbaritem logo="fas fa-flask" href="/" :active="request()->is('/analyses')">Analyses</x-slidbaritem>
        <x-slidbaritem logo="fas fa-chart-line" href="/" :active="request()->is('/rapports')">Rapports</x-slidbaritem>
        <x-slidbaritem logo="fas fa-cog" href="/" :active="request()->is('/settings')">Paramètres</x-slidbaritem>
        <li><a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/20 transition"><i class=""></i> </a></li>
        <li><a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/20 transition"><i class=""></i> </a></li>
        <li><a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/20 transition"><i class=""></i> </a></li>
        <li><a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/20 transition"><i class=""></i> </a></li>
        <li><a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/20 transition"><i class=""></i> </a></li>
    </ul>

    <!-- Logout -->
    <div class="mt-auto px-6 py-4 border-t border-white/20">
        <a href="#" class="flex items-center gap-3 text-white opacity-80 hover:opacity-100 transition"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
    </div>
</div>
