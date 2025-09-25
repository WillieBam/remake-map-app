<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-10 w-auto fill-current text-gray-600" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('countries.index')" :active="request()->routeIs('countries.index')">
                        {{ __('Map') }}
                    </x-nav-link>
                    @can('admin')
                    <x-nav-link :href="route('news.index')" :active="request()->routeIs('news.index')">
                        {{ __('News') }}
                    </x-nav-link>
                    <x-nav-link :href="route('user.users', [Auth::user()->user_id])" :active="request()->routeIs('user.users')">
                        {{ __('Users') }}
                    </x-nav-link>
                    @endif
                    @if(auth()->check())
                    @if(auth()->user()->role_id == 1)
                    <x-nav-link :href="route('user.admins')" :active="request()->routeIs('user.admins')">
                        {{ __('Admins') }}
                    </x-nav-link>
                    @endif
                    <x-nav-link :href="route('user.profile', [Auth::user()->user_id])" :active="request()->routeIs('user.profile')">
                        {{ __('Profile') }}
                    </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <!-- Authentication -->
                @if (auth()->check())
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </button>
                </form>
                @else
                <form method="GET" action="{{ route('login') }}">
                    @csrf

                    <button style="margin-right: 20px;" :href="route('login')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log In') }}
                    </button>
                </form>
                <form method="GET" action="{{ route('register') }}">
                    @csrf

                    <button :href="route('register')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Register') }}
                    </button>
                </form>
                @endif
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="mt-3 space-y-1">
                <!-- Authentication -->
                @if (auth()->check())
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                </form>
                @else
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                        <x-responsive-nav-link :href="route('login')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                </form>
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                        <x-responsive-nav-link :href="route('register')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                </form>
                @endif
            </div>
        </div>
    </div>
</nav>
