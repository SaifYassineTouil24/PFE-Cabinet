<!-- Header -->


<div class="flex justify-between items-center bg-blue-100 p-2 text-blue-600 border-b border-blue-300 shadow-md ">
    <!-- Left Section: Breadcrumb -->
    <div class="flex items-center gap-2">
        <i class="fas fa-compass text-blue-600 text-lg"></i>
        <div id="page-breadcrumb" class="text-sm font-medium">
            <!-- Breadcrumb dynamically updated -->
            <nav class="text-sm font-medium flex items-center gap-1 text-blue-600">
                <a href="{{ url('/') }}" class="hover:underline"></a>
            </nav>
            <a href="{{ url('/') }}" class="hover:underline">Accueil</a>
            <span>/</span>
            @php
                $segments = request()->segments();
            @endphp
            @foreach($segments as $key => $segment)
                @if($key + 1 < count($segments))
                    <a href="{{ url(implode('/', array_slice($segments, 0, $key + 1))) }}" class="hover:underline">
                        {{ ucfirst(str_replace('-', ' ', $segment)) }}
                    </a>
                    <span>/</span>
                @else
                    <span class="text-blue-500">{{ ucfirst(str_replace('-', ' ', $segment)) }}</span>
                @endif
            @endforeach
        </div>
    </div>

    <!-- Right Section: Controls -->
    <div class="flex items-center gap-4">


        <!-- Live Clock -->
        <div class="flex items-center gap-3 bg-blue-600 backdrop-blur-md px-4 py-2 rounded-full shadow-md transition hover:shadow-lg h-8 w-32">
            <i class="fas fa-clock text-blue-100 text-lg"></i>
            <span id="live-clock" class="font-medium text-blue-100 text-sm w-[85px] text-center tracking-wide">
        --
    </span>
        </div>

        <script>
            function updateClock() {
                const clockElement = document.getElementById('live-clock');
                const now = new Date();
                clockElement.innerText = now.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            }

            updateClock(); // Initial call to avoid delay
            setInterval(updateClock, 1000);
        </script>


        <!-- Action Buttons -->
        <button class="flex items-center gap-2 px-4 py-2 bg-[#4361ee] text-blue-100 rounded-full text-xs font-semibold shadow-md transition-all duration-200 hover:bg-[#3a0ca3] hover:shadow-lg hover:-translate-y-1">
            <i class="fas fa-user-plus text-sm"></i>
            <span>Ajouter Patient</span>
        </button>

        <button class="flex items-center gap-2 px-4 py-2 bg-[#4361ee] text-blue-100 rounded-full  text-xs font-semibold shadow-md transition-all duration-200 hover:bg-[#3a0ca3] hover:shadow-lg hover:-translate-y-1">
            <i class="fas fa-calendar-plus text-sm"></i>
            <span>Ajouter Rendez-vous</span>
        </button>

        <!-- Notifications -->
        <div class="relative w-9 h-9 flex items-center justify-center rounded-full bg-[#4361ee] backdrop-blur-md cursor-pointer transition hover:bg-[#3a0ca3] hover:shadow-lg">
            <i class="fas fa-bell text-blue-100 text-lg"></i>
            <span class="absolute top-0 right-0 bg-red-500 text-white text-xs font-bold w-5 h-5 flex items-center justify-center rounded-full border-2 border-indigo-600">
                3
            </span>
        </div>

        <!-- Profile Section -->
        <div class="flex items-center gap-2 bg-[#4361ee] text-blue-100 backdrop-blur-md p-2.5 rounded-xl shadow-md cursor-pointer transition hover:bg-[#3a0ca3] hover:shadow-lg">
            <div class="flex flex-col">
                <span class="font-semibold text-xs">{{ $name }}</span>
                <span class="text-[10px]">{{ $role }}</span>
            </div>
            <img src="{{ $profilePicture }}" alt="User Profile" class="w-8 h-8 rounded-full border-2 border-white object-cover">
        </div>
    </div>
</div>
