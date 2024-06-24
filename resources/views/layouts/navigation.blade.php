<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Vite integration -->
    @vite(['resources/css/main.css', 'resources/js/main.js'])
</head>

<body>
    <nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Left Side: Logo and Primary Navigation -->
                <div class="flex">
                    <!-- Logo -->
                    <div class="shrink-0 flex items-center">
                        <a href="{{ route('dashboard') }}">
                            <img src="{{ asset('images/fslogo.png') }}" alt="Logo" class="block h-9 w-auto">
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            <span class="text-xl font-semibold ms-2">SurveilUCD</span>
                        </x-nav-link>
                    </div>

                    <!-- Mentor Template Navigation -->
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex custom-translate-left">
                        <x-nav-link href="/sessions" :active="request()->routeIs('/sessions')">Session</x-nav-link>
                        <x-nav-link href="/departments" :active="request()->routeIs('/departments')">Departement</x-nav-link>
                        <x-nav-link href="/locales" :active="request()->routeIs('/locales')">Locale</x-nav-link>
                        <x-nav-link href="/enseignants" :active="request()->routeIs('/enseignants')">Locale</x-nav-link>
                        {{-- <x-nav-link href="#" :active="request()->routeIs('trainers.html')">Trainers</x-nav-link>
                        <x-nav-link href="#" :active="request()->routeIs('events.html')">Events</x-nav-link>
                        <x-nav-link href="#" :active="request()->routeIs('pricing.html')">Pricing</x-nav-link>
                        <x-nav-link href="#" :active="request()->routeIs('contact.html')">Contact</x-nav-link> --}}
                    </div>
                </div>

                <!-- Right Side: User Dropdown -->
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>
                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                       this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>

                <!-- Hamburger Menu for Mobile -->
                <div class="-me-2 flex items-center sm:hidden">
                    <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Responsive Navigation Menu -->
        <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
            <div class="pt-2 pb-3 space-y-1 ml-4">
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="index.html" :active="request()->routeIs('index.html')">Home</x-responsive-nav-link>
                <x-responsive-nav-link href="about.html" :active="request()->routeIs('about.html')">About</x-responsive-nav-link>
                <x-responsive-nav-link href="courses.html" :active="request()->routeIs('courses.html')">Courses</x-responsive-nav-link>
                <x-responsive-nav-link href="trainers.html" :active="request()->routeIs('trainers.html')">Trainers</x-responsive-nav-link>
                <x-responsive-nav-link href="events.html" :active="request()->routeIs('events.html')">Events</x-responsive-nav-link>
                <x-responsive-nav-link href="pricing.html" :active="request()->routeIs('pricing.html')">Pricing</x-responsive-nav-link>
                <x-responsive-nav-link href="contact.html" :active="request()->routeIs('contact.html')">Contact</x-responsive-nav-link>
            </div>
            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>
                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                                     this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        </div>
    </nav>
</body>

</html>
