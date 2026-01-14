<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                {{-- Desktop nav --}}
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <x-nav-link :href="route('admin.lots.index')" :active="request()->routeIs('admin.lots.*')">
                                {{ __('Lots') }}
                            </x-nav-link>

                            <x-nav-link :href="route('admin.auctions.index')" :active="request()->routeIs('admin.auctions.*')">
                                {{ __('Auctions') }}
                            </x-nav-link>

                            <x-nav-link :href="route('admin.sales.index')" :active="request()->routeIs('admin.sales.*')">
                                {{ __('Sales') }}
                            </x-nav-link>

                            <x-nav-link :href="route('admin.bids.index')" :active="request()->routeIs('admin.bids.*')">
                                {{ __('Bids') }}
                            </x-nav-link>

                            <x-nav-link :href="route('admin.reports.sales')" :active="request()->routeIs('admin.reports.*')">
                                {{ __('Reports') }}
                            </x-nav-link>
                        @else
                            <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                                {{ __('Home') }}
                            </x-nav-link>

                            <x-nav-link :href="route('public.catalogue')" :active="request()->routeIs('public.catalogue') || request()->routeIs('public.lots.*')">
                                {{ __('Lots') }}
                            </x-nav-link>
                        @endif
                    @else
                        <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                            {{ __('Home') }}
                        </x-nav-link>

                        <x-nav-link :href="route('public.catalogue')" :active="request()->routeIs('public.catalogue') || request()->routeIs('public.lots.*')">
                            {{ __('Lots') }}
                        </x-nav-link>
                    @endauth
                </div>
            </div>

            {{-- Account dropdown --}}
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>
                                @auth
                                    {{ auth()->user()->name }}
                                @else
                                    {{ __('Account') }}
                                @endauth
                            </div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        @auth
                            @if (Route::has('profile.edit'))
                                <x-dropdown-link :href="route('profile.edit')">
                                    {{ __('Profile') }}
                                </x-dropdown-link>
                            @endif

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        @else
                            <div class="px-4 py-2 flex gap-3">
                                @if (Route::has('login'))
                                    <a class="text-sm text-gray-700 underline" href="{{ route('login') }}">Log in</a>
                                @endif
                                @if (Route::has('register'))
                                    <a class="text-sm text-gray-700 underline" href="{{ route('register') }}">Register</a>
                                @endif
                            </div>
                        @endauth
                    </x-slot>
                </x-dropdown>
            </div>

            {{-- Mobile hamburger --}}
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile nav --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @auth
                @if(auth()->user()->isAdmin())
                    <x-responsive-nav-link :href="route('admin.lots.index')" :active="request()->routeIs('admin.lots.*')">
                        {{ __('Lots') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.auctions.index')" :active="request()->routeIs('admin.auctions.*')">
                        {{ __('Auctions') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.sales.index')" :active="request()->routeIs('admin.sales.*')">
                        {{ __('Sales') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.bids.index')" :active="request()->routeIs('admin.bids.*')">
                        {{ __('Bids') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.reports.sales')" :active="request()->routeIs('admin.reports.*')">
                        {{ __('Reports') }}
                    </x-responsive-nav-link>
                @else
                    <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                        {{ __('Home') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('public.catalogue')" :active="request()->routeIs('public.catalogue') || request()->routeIs('public.lots.*')">
                        {{ __('Lots') }}
                    </x-responsive-nav-link>
                @endif
            @else
                <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                    {{ __('Home') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('public.catalogue')" :active="request()->routeIs('public.catalogue') || request()->routeIs('public.lots.*')">
                    {{ __('Lots') }}
                </x-responsive-nav-link>
            @endauth
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            @auth
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ auth()->user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ auth()->user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    @if (Route::has('profile.edit'))
                        <x-responsive-nav-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-responsive-nav-link>
                    @endif

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="px-4 py-3 flex gap-3">
                    @if (Route::has('login'))
                        <a class="text-sm text-gray-700 underline" href="{{ route('login') }}">Log in</a>
                    @endif
                    @if (Route::has('register'))
                        <a class="text-sm text-gray-700 underline" href="{{ route('register') }}">Register</a>
                    @endif
                </div>
            @endauth
        </div>
    </div>
</nav>
