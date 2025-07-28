<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion | MediAssist</title>
    @vite('resources/css/app.css')
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js" crossorigin="anonymous"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-100 h-screen flex justify-center items-center m-0 p-0">
<div class="w-full max-w-md bg-white rounded-2xl shadow-md p-6">
    <div class="text-center mb-6">
        <a href="/" class="text-2xl font-bold text-blue-600">
            <i class="fas fa-clinic-medical mr-2"></i> MediAssist
        </a>
    </div>

    <div class="text-center mb-6">
        <h2 class="text-xl font-semibold text-gray-700">Se connecter</h2>
    </div>

    <!-- Validation Errors -->
    @if($errors->any())
        <div class="mb-4 text-red-500 text-sm">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-600">Email</label>
            <input id="email" class="block w-full mt-1 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" type="email" name="email"
                   value="{{ old('email') }}" required autofocus>
        </div>

        <!-- Password -->
        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-600">Mot de passe</label>
            <input id="password" class="block w-full mt-1 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" type="password" name="password" required autocomplete="current-password">
        </div>

        <!-- Remember Me -->
        <div class="mb-4 flex items-center">
            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring focus:ring-blue-200 focus:ring-opacity-50" name="remember">
            <label for="remember_me" class="ml-2 text-sm text-gray-600">Se souvenir de moi</label>
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-600 text-white rounded-lg py-2 px-6 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                Se connecter
            </button>
        </div>
    </form>
</div>
</body>
</html>
