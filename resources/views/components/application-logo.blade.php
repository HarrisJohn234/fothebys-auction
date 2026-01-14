@props([
    'size' => 'md', // md | lg | xl | hero
    'showText' => true
])

@php
    $sizes = [
        'md'   => 'h-12',
        'lg'   => 'h-16',
        'xl'   => 'h-20',
        'hero' => 'h-28',
    ];
@endphp

<div class="flex items-center gap-4">
    {{-- Crest --}}
    <img
        src="{{ asset('images/branding/fothebys-crest.png') }}"
        alt="Fotheby’s Auction House Crest"
        class="{{ $sizes[$size] }} w-auto object-contain drop-shadow-sm"
    />

    @if($showText)
        {{-- Wordmark --}}
        <div class="leading-tight">
            <div class="text-brand-purple font-semibold tracking-wide text-xl">
                Fotheby’s
            </div>
            <div class="text-xs tracking-[0.3em] text-gray-500 uppercase">
                Auction House
            </div>
        </div>
    @endif
</div>
