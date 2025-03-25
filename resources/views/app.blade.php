<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clinic Propre</title>
    @vite('resources/css/app.css') <!-- Ensure Tailwind is compiled -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body class="bg-gray-100 text-gray-800">

<div class="flex">
    <!-- Sidebar -->
    <x-sidebar />

    <div class="flex-1 min-h-screen">
        <!-- Header -->
        <x-header />


        <!-- Main Content -->

    </div>
</div>

</body>
</html>
