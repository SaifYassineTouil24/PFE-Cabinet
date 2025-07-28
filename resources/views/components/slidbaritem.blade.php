@props(['active' => false , 'href','logo'])

<li>
    <a href="{{$href}}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/20 {{ $active ? 'bg-blue-400 text-white': ''}} transition"
       aria-current="{{ $active ? 'page': 'false' }}">
        <i class="{{ $logo }}"></i> {{ $slot }}
    </a>
</li>
