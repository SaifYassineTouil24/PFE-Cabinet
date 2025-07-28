@php

    $statusColors = [
        'Programmé' => 'bg-blue-100 border-blue-400 text-blue-700',
        'Salle dattente' => 'bg-yellow-100 border-yellow-400 text-yellow-700',
        'En préparation' => 'bg-orange-100 border-orange-400 text-orange-700',
        'En consultation' => 'bg-purple-100 border-purple-400 text-purple-700',
        'Terminé' => 'bg-green-100 border-green-400 text-green-700',
        'Annulé' => 'bg-red-100 border-red-400 text-red-700',
    ];

    $colors = explode(' ', $statusColors[$status]);
@endphp

<div class="patient-card relative overflow-hidden bg-white rounded-md shadow border-l-3 {{ $colors[1] }} transition-all duration-200 hover:shadow-md"
     data-status="{{ $status }}"
     data-appointment-id="{{ $appointment->ID_RV ?? '' }}">

    <div class="flex items-center justify-between py-2 px-3">
        <div>
            <h4 class="text-sm font-semibold text-gray-800">{{ $name }}</h4>
            <span class="inline-block text-xs px-2 py-0.5 mt-1 rounded-full {{ $colors[0] }} {{ $colors[2] }}">
                {{ $type }}
            </span>
        </div>
        <div class="w-1 h-8 absolute top-0 right-0 {{ $colors[0] }} opacity-60"></div>
    </div>
</div>
