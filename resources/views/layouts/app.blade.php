<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Contabilidad TCP') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 font-sans antialiased overflow-x-hidden">
    <div class="flex h-screen" x-data="{ collapsed: localStorage.getItem('sidebarCollapsed') === 'true', toggle() { this.collapsed = !this.collapsed; localStorage.setItem('sidebarCollapsed', this.collapsed) } }">
        <!-- Sidebar -->
        <aside :class="collapsed ? 'w-16' : 'w-64'" class="bg-slate-900 text-white flex flex-col transition-all duration-300 flex-shrink-0 relative">
            <!-- Logo -->
            <div class="p-6 border-b border-slate-800">
                <h1 x-show="!collapsed" class="text-xl font-bold text-white">Contabilidad</h1>
                <p x-show="!collapsed" class="text-xs text-slate-400">Gestión para TCP/Mipymes</p>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-2 space-y-1">
                <a href="{{ route('dashboard') }}" class="sidebar-link relative group {{ request()->routeIs('dashboard') ? 'active' : '' }}" :class="collapsed ? 'justify-center px-2' : ''">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span :class="collapsed ? 'hidden' : ''" class="text-sm whitespace-nowrap">Dashboard</span>
                    <div x-show="collapsed" class="absolute left-full ml-2 px-2.5 py-1.5 bg-slate-800 text-white text-xs rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 shadow-lg">
                        Dashboard
                    </div>
                </a>

                <a href="{{ route('transacciones.index') }}" class="sidebar-link relative group {{ request()->routeIs('transacciones.*') ? 'active' : '' }}" :class="collapsed ? 'justify-center px-2' : ''">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    <span :class="collapsed ? 'hidden' : ''" class="text-sm whitespace-nowrap">Transacciones</span>
                    <div x-show="collapsed" class="absolute left-full ml-2 px-2.5 py-1.5 bg-slate-800 text-white text-xs rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 shadow-lg">
                        Transacciones
                    </div>
                </a>

                @auth
                @php $empresa = Auth::user()->empresa; @endphp
                @if($empresa && $empresa->esContabilidadFormal())
                <a href="{{ route('contabilidad.index') }}" class="sidebar-link relative group {{ request()->routeIs('contabilidad.*') ? 'active' : '' }}" :class="collapsed ? 'justify-center px-2' : ''">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span :class="collapsed ? 'hidden' : ''" class="text-sm whitespace-nowrap">Contabilidad</span>
                    <div x-show="collapsed" class="absolute left-full ml-2 px-2.5 py-1.5 bg-slate-800 text-white text-xs rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 shadow-lg">
                        Contabilidad
                    </div>
                </a>
                @endif
                @endauth

                <a href="{{ route('productos.index') }}" class="sidebar-link relative group {{ request()->routeIs('productos.*') ? 'active' : '' }}" :class="collapsed ? 'justify-center px-2' : ''">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <span :class="collapsed ? 'hidden' : ''" class="text-sm whitespace-nowrap">Inventario</span>
                    <div x-show="collapsed" class="absolute left-full ml-2 px-2.5 py-1.5 bg-slate-800 text-white text-xs rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 shadow-lg">
                        Inventario
                    </div>
                </a>

                <a href="{{ route('categorias.index') }}" class="sidebar-link relative group {{ request()->routeIs('categorias.*') ? 'active' : '' }}" :class="collapsed ? 'justify-center px-2' : ''">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    <span :class="collapsed ? 'hidden' : ''" class="text-sm whitespace-nowrap">Categorías</span>
                    <div x-show="collapsed" class="absolute left-full ml-2 px-2.5 py-1.5 bg-slate-800 text-white text-xs rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 shadow-lg">
                        Categorías
                    </div>
                </a>

                <a href="#" class="sidebar-link relative group" :class="collapsed ? 'justify-center px-2' : ''">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span :class="collapsed ? 'hidden' : ''" class="text-sm whitespace-nowrap">Reportes</span>
                    <div x-show="collapsed" class="absolute left-full ml-2 px-2.5 py-1.5 bg-slate-800 text-white text-xs rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 shadow-lg">
                        Reportes
                    </div>
                </a>

                @auth
                @if(Auth::user()->isAdmin())
                <a href="{{ route('empresa.index') }}" class="sidebar-link relative group {{ request()->routeIs('empresa.*') ? 'active' : '' }}" :class="collapsed ? 'justify-center px-2' : ''">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span :class="collapsed ? 'hidden' : ''" class="text-sm whitespace-nowrap">Empresa</span>
                    <div x-show="collapsed" class="absolute left-full ml-2 px-2.5 py-1.5 bg-slate-800 text-white text-xs rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 shadow-lg">
                        Empresa
                    </div>
                </a>
                @endif
                @endauth

            </nav>

            <!-- Collapse button -->
            <button @click="toggle()"
                class="absolute -right-3 top-[30px] w-6 h-6 bg-slate-800 border border-slate-700 rounded-full flex items-center justify-center text-slate-300 hover:text-white hover:bg-slate-700 transition-colors z-50 shadow-md">
                <svg :class="collapsed ? 'rotate-180' : ''" xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <!-- User Info -->
            <div class="p-3 border-t border-slate-800">
                @auth
                <div class="flex items-center gap-3 mb-2" :class="collapsed ? 'justify-center' : ''">
                    <div class="w-8 h-8 rounded-full bg-slate-700 flex items-center justify-center flex-shrink-0 relative group">
                        <span class="text-sm font-medium">{{ strtoupper(Auth::user()->name[0]) }}</span>
                        <div x-show="collapsed" class="absolute left-full ml-2 px-2.5 py-1.5 bg-slate-800 text-white text-xs rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 shadow-lg">
                            {{ Auth::user()->name }} ({{ Auth::user()->role }})
                        </div>
                    </div>
                    <div :class="collapsed ? 'hidden' : ''" class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-slate-400 capitalize">{{ Auth::user()->role }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-sm text-slate-300 hover:text-white hover:bg-slate-800 rounded-lg transition-colors" :class="collapsed ? 'justify-center px-2' : ''">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span :class="collapsed ? 'hidden' : ''">Cerrar Sesión</span>
                        <div x-show="collapsed" class="absolute left-full ml-2 px-2.5 py-1.5 bg-slate-800 text-white text-xs rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 shadow-lg">
                            Cerrar Sesión
                        </div>
                    </button>
                </form>
                @endauth
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white border-b border-slate-200 px-8 py-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-slate-800">
                        @yield('title', 'Dashboard')
                    </h2>
                    <div class="flex items-center gap-4">
                        <span class="text-xs text-slate-500">
                            {{ now()->format('d/m/Y') }}
                        </span>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <div class="flex-1 overflow-auto p-8">
                @yield('content')
            </div>
        </main>
    </div>
    @stack('scripts')
</body>
</html>