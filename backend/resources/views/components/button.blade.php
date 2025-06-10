@props([
    'type' => 'button',
    'variant' => '',
    'href' => null,
])

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => 'btn ' . ($variant ? 'btn-'.$variant : '')]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => 'btn ' . ($variant ? 'btn-'.$variant : '')]) }}>
        {{ $slot }}
    </button>
@endif
