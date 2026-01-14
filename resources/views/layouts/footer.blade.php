<footer class="bg-brand-purpleDark mt-20">
    <div class="max-w-screen-xl mx-auto px-6 py-14 text-center">

        {{-- Logo --}}
        <div class="flex justify-center mb-6">
            <img
                src="{{ asset('images/Branding/fothebys-crest.png') }}"
                alt="Fotheby’s Crest"
                class="max-h-40 max-w-xs w-auto h-auto mx-auto"
            >

        </div>

        {{-- Brand name --}}
        <div class="text-brand-gold font-serif text-2xl tracking-wide">
            Fotheby’s Auction House
        </div>

        {{-- Tagline --}}
        <div class="text-sm text-brand-goldSoft mt-2 tracking-wide">
            Established 1744 · Fine Art & Collectibles
        </div>

        {{-- Divider --}}
        <div class="mt-8 mb-6 flex justify-center">
            <div class="w-24 h-px bg-brand-gold/40"></div>
        </div>

        {{-- Copyright --}}
        <div class="text-xs text-gray-300">
            © {{ now()->year }} Fotheby’s Auction House. Prototype system for CSY3065.
        </div>

    </div>
</footer>
