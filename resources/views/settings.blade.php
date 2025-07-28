<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paramètres - Clinic Propre</title>
    @vite('resources/css/app.css')
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="flex h-screen bg-gray-100 m-0 p-0">

<!-- Sidebar -->
<x-sidebar class="w-64 fixed left-0 top-0 h-full bg-gray-200 shadow-md" />

<!-- Main content -->
<div class="flex-1 flex flex-col pl-64 bg-blue-100">
    <x-header />

    <div class="flex-1 overflow-auto p-6">
        <div class="bg-white rounded-2xl shadow-md p-6 max-w-4xl mx-auto">
            <h2 class="text-2xl font-semibold text-gray-700 mb-6 flex items-center">
                <i class="fas fa-cog mr-2"></i> Paramètres du Système
            </h2>

            <!-- Settings Form -->
            <form method="POST" action="{{ route('settings.update') }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="clinic_name" class="block text-sm font-medium text-gray-600 mb-1">Nom de la clinique</label>
                    <input type="text" id="clinic_name" name="clinic_name" value="{{ old('clinic_name', $settings->clinic_name ?? '') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-600 mb-1">Email de contact</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $settings->email ?? '') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-600 mb-1">Numéro de téléphone</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $settings->phone ?? '') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-gray-600 mb-1">Adresse</label>
                    <textarea id="address" name="address"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
                              rows="3">{{ old('address', $settings->address ?? '') }}</textarea>
                </div>

                <div class="text-right">
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded-lg transition-all duration-200 shadow">
                        Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
