@props(['active'])

@php
$classes = ($active ?? false)
    ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-brand-gold text-start text-base font-semibold text-brand-purple bg-brand-goldSoft focus:outline-none transition'
    : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-700 hover:text-brand-purple hover:bg-brand-purpleSoft hover:border-brand-gold/50 focus:outline-none transition';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
