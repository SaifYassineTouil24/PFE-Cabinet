<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="flex justify-between items-center bg-gradient-to-r from-blue-600 to-blue-600 shadow-lg p-4 text-white">
    <!-- Left Section: Breadcrumb -->
    <div class="flex items-center gap-3">
        <i class="fas fa-compass text-white text-xl"></i>
        <div id="page-breadcrumb" class="text-lg font-medium">
            <!-- Breadcrumb dynamically updated -->
        </div>
    </div>

    <!-- Right Section: Controls -->
    <div class="flex items-center gap-6">
        <!-- Search Bar -->
        <div class="flex items-center bg-white/20 backdrop-blur-md rounded-full px-4 py-2 border focus-within:ring-2 focus-within:ring-white/50 w-80 transition">
            <i class="fas fa-search text-white"></i>
            <input type="text" placeholder="Search..." class="flex-1 outline-none px-2 text-sm bg-transparent text-white placeholder-white">
        </div>

        <!-- Live Clock -->
        <div class="flex items-center gap-2 bg-white/20 backdrop-blur-md p-3 rounded-xl shadow-md transition hover:shadow-lg">
            <i class="fas fa-clock text-white"></i>
            <span id="live-clock" class="font-medium">
                <script>
                    setInterval(() => {
                        document.getElementById('live-clock').innerText = new Date().toLocaleTimeString();
                    }, 1000);
                </script>
            </span>
        </div>

        <!-- Action Buttons -->
        <button class="flex items-center gap-2 px-5 py-2.5 bg-white text-blue-600 rounded-full text-sm font-semibold shadow-md transition-all duration-200 hover:bg-gray-100 hover:shadow-lg hover:-translate-y-1">
            <i class="fas fa-user-plus text-base"></i>
            <span>Ajouter Patient</span>
        </button>

        <button class="flex items-center gap-2 px-5 py-2.5 bg-white text-blue-600 rounded-full text-sm font-semibold shadow-md transition-all duration-200 hover:bg-gray-100 hover:shadow-lg hover:-translate-y-1">
            <i class="fas fa-calendar-plus text-base"></i>
            <span>Ajouter Rendez-vous</span>
        </button>

        <!-- Notifications -->
        <div class="relative w-10 h-10 flex items-center justify-center rounded-full bg-white/20 backdrop-blur-md cursor-pointer transition hover:bg-white/30 hover:shadow-lg">
            <i class="fas fa-bell text-white text-xl"></i>
            <span class="absolute top-0 right-0 bg-red-500 text-white text-xs font-bold w-5 h-5 flex items-center justify-center rounded-full border-2 border-indigo-600">
                3
            </span>
        </div>

        <!-- Profile Section -->
        <div class="flex items-center gap-3 bg-white/20 backdrop-blur-md p-3 rounded-xl shadow-md cursor-pointer transition hover:shadow-lg">
            <div class="flex flex-col">
                <span class="font-semibold text-sm">{{ $name }}</span>
                <span class="text-xs">{{ $role }}</span>
            </div>
            <img src="{{ $profilePicture }}" alt="User Profile" class="w-10 h-10 rounded-full border-2 border-white object-cover">
        </div>
    </div>
</div>
