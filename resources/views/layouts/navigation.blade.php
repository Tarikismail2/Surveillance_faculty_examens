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

                    @if(Auth::user()->role === 'admin')
                    <!-- Admin Template Navigation -->
                    <div class="hidden space-x-8 sm:flex sm:items-center sm:ms-10">
                        <x-nav-link href="/sessions" :active="request()->routeIs('sessions')">Session</x-nav-link>
                        <x-nav-link href="/departments" :active="request()->routeIs('departments')">Département</x-nav-link>
                        <x-nav-link href="/enseignants" :active="request()->routeIs('enseignants')">Enseignant</x-nav-link>
                        <x-nav-link href="/salles" :active="request()->routeIs('salles')">Locale</x-nav-link>
                        <x-nav-link href="/etudiants" :active="request()->routeIs('etudiants')">Etudiant</x-nav-link>
                        <x-nav-link href="/filiere" :active="request()->routeIs('filiere')">Filiere</x-nav-link>
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                    <div>Contraintes</div>
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('contrainte_enseignants.index_admin')">{{ __('Contrainte Enseignant') }}</x-dropdown-link>
                                <x-dropdown-link :href="route('contrainte_salles.index')">{{ __('Contrainte Salle') }}</x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                    <div>Planification</div>
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('examens.global')">{{ __('Emploi Globale') }}</x-dropdown-link>
                                <x-dropdown-link :href="route('selectFiliere')">{{ __('Emploi des etudiants') }}</x-dropdown-link>
                                <x-dropdown-link :href="route('selectDepartment')">{{ __('Emploi d\'Enseignant Dépertement') }}</x-dropdown-link>
                                <x-dropdown-link :href="route('surveillants_reservistes.index')">{{ __('Emploi des Réservistes') }}</x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>
                    @elseif(Auth::user()->role === 'etudiant')
                    <div class="hidden space-x-8 sm:flex sm:items-center sm:ms-10">
                        <!-- Navigation links for students -->
                        <x-nav-link href="/planification/select_student" :active="request()->routeIs('selectStudent')">Emploi d'Etudiant</x-nav-link>
                    </div>
                    @elseif(Auth::user()->role === 'enseignant')
                    <div class="hidden space-x-8 sm:flex sm:items-center sm:ms-10">
                        <!-- Navigation links for enseignants -->
                        <x-nav-link href="/select-enseignant" :active="request()->routeIs('selectEnseignant')">Emploi d'Enseignant</x-nav-link>
                        <x-nav-link href="/contraintes" :active="request()->routeIs('contraintes.index')">Contrainte Enseignant</x-nav-link>
                    </div>
                    @endif

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

           
    </nav>
</body>

</html>