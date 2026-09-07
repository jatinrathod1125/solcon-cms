<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="firebase-config" content="{{ json_encode(config('services.firebase')) }}">
    <meta name="theme-color" content="#0f172a">
    <title>@yield('title', 'Dashboard') | {{ currentBrand()->name }} Industries</title>

    <!-- PWA Primary Meta & App Icons -->
    <link rel="manifest" href="/manifest.json">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Solcon">
    <meta name="application-name" content="Solcon">
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/icons/icon-192x192.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/icons/icon-192x192.png">
    <link rel="apple-touch-icon" sizes="167x167" href="/icons/icon-192x192.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.iconify.design/iconify-icon/3.0.0/iconify-icon.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @php
        $appBrand = currentBrand();
        $isFixoraBrand = ($appBrand && $appBrand->id == 2);
    @endphp
    <style>
        :root {
            --brand: {{ $isFixoraBrand ? '#059669' : '#ea580c' }};
            --brand-dark: {{ $isFixoraBrand ? '#047857' : '#c2410c' }};
            --brand-soft: {{ $isFixoraBrand ? '#ecfdf5' : '#fff7ed' }};
            --brand-border: {{ $isFixoraBrand ? '#a7f3d0' : '#fed7aa' }};
            --brand-ring: {{ $isFixoraBrand ? 'rgba(5, 150, 105, 0.2)' : 'rgba(234, 88, 12, 0.2)' }};
        }
        @if(($uiSettings['compact_mode'] ?? 'disable') === 'enable')
            .page-content { padding-top: 1rem !important; }
            .erp-card { padding: 1rem !important; }
        @endif
    </style>
    @yield('styles')
</head>
<body class="h-full bg-slate-50 text-slate-900 antialiased {{ $isFixoraBrand ? 'selection:bg-emerald-100 selection:text-emerald-900 brand-fixora' : 'selection:bg-orange-100 selection:text-orange-900 brand-solcon' }}">
    <div class="erp-shell min-h-full">
        <x-sidebar />
        <div id="sidebarOverlay" class="sidebar-overlay"></div>

        <div id="workspace" class="workspace min-w-0 transition-all duration-300">
            <header class="app-header sticky top-0 z-40 transition-all duration-300 {{ $isFixoraBrand ? 'bg-slate-950/95 border-b border-emerald-500/20 text-white backdrop-blur-xl shadow-lg shadow-slate-950/25' : 'bg-white/90 border-b border-orange-100/90 text-slate-900 backdrop-blur-xl shadow-sm' }}">
                <div class="flex h-16 sm:h-[72px] items-center gap-1.5 sm:gap-3 px-2.5 sm:px-6 lg:px-8">
                    <!-- Mobile Hamburger Menu Button -->
                    <button id="mobileSidebarToggle" type="button" class="flex h-9 w-9 sm:h-10 sm:w-10 shrink-0 items-center justify-center rounded-xl {{ $isFixoraBrand ? 'text-slate-400 hover:bg-white/10 hover:text-white' : 'text-slate-500 hover:bg-orange-50 hover:text-orange-600' }} transition lg:hidden" aria-label="Open navigation">
                        <i data-lucide="menu" class="h-5 w-5"></i>
                    </button>

                    <button id="sidebarToggle" type="button" class="hidden h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $isFixoraBrand ? 'text-slate-400 hover:bg-white/10 hover:text-white' : 'text-slate-500 hover:bg-orange-50 hover:text-orange-600' }} transition lg:flex" aria-label="Collapse navigation">
                        <i data-lucide="panel-left-close" class="h-5 w-5"></i>
                    </button>

                    <div class="min-w-0 flex-1">
                        <div class="hidden sm:flex items-center gap-2 text-[11px] font-extrabold uppercase tracking-[0.16em]">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full {{ $isFixoraBrand ? 'bg-emerald-500/15 border border-emerald-500/30 text-emerald-300' : 'bg-orange-50 border border-orange-200 text-orange-600' }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ $isFixoraBrand ? 'bg-emerald-400 animate-pulse' : 'bg-orange-500 animate-pulse' }}"></span>
                                {{ currentBrand()->name }}
                            </span>
                            <span class="{{ $isFixoraBrand ? 'text-slate-600' : 'text-slate-300' }}">/</span>
                            <span class="truncate {{ $isFixoraBrand ? 'text-slate-400' : 'text-slate-500' }}">@yield('title', 'Dashboard')</span>
                        </div>
                        <h1 class="truncate text-sm sm:text-lg lg:text-xl font-extrabold tracking-tight {{ $isFixoraBrand ? 'text-white' : 'text-slate-950' }}">@yield('header-title', 'Production Management System')</h1>
                    </div>

                    <div class="hidden items-center gap-2 text-right xl:flex shrink-0">
                        <div class="text-sm font-extrabold {{ $isFixoraBrand ? 'text-slate-200' : 'text-slate-800' }}">{{ now()->format('D, d M') }}</div>
                        <span class="h-1 w-1 rounded-full {{ $isFixoraBrand ? 'bg-emerald-500/50' : 'bg-orange-400/50' }}"></span>
                        <div id="liveClock" class="min-w-[70px] text-sm font-bold tabular-nums {{ $isFixoraBrand ? 'text-emerald-400' : 'text-orange-600' }}">{{ now()->format('h:i A') }}</div>
                    </div>

                    @if(Auth::check() && availableBrands()->count() > 1)
                        <div class="relative shrink-0" id="brandSwitcherContainer">
                            <button type="button" id="brandSwitcherBtn" class="flex items-center gap-1.5 sm:gap-2 {{ $isFixoraBrand ? 'bg-gradient-to-r from-emerald-950/90 via-slate-900 to-emerald-950/80 border border-emerald-500/40 text-white shadow-lg shadow-emerald-950/40 hover:border-emerald-400' : 'bg-gradient-to-r from-orange-50 via-amber-50 to-orange-50 border border-orange-200 text-orange-950 shadow-sm hover:border-orange-300' }} px-2 sm:px-3 py-1 sm:py-1.5 rounded-xl sm:rounded-2xl transition-all duration-200" aria-expanded="false">
                                <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-lg {{ $isFixoraBrand ? 'bg-emerald-500/20 text-emerald-300' : 'bg-orange-500/20 text-orange-600' }}">
                                    <i data-lucide="layers" class="h-3.5 w-3.5"></i>
                                </span>
                                <div class="text-left shrink-0">
                                    <span class="hidden sm:block text-[8px] font-black uppercase tracking-widest leading-none {{ $isFixoraBrand ? 'text-emerald-400' : 'text-orange-600' }}">Brand</span>
                                    <span class="block text-xs font-extrabold leading-tight {{ $isFixoraBrand ? 'text-white' : 'text-slate-900' }}">{{ currentBrand()->name }}</span>
                                </div>
                                <i data-lucide="chevron-down" id="brandChevronIcon" class="h-3.5 w-3.5 shrink-0 ml-0.5 opacity-60 transition-transform duration-200"></i>
                            </button>

                            <div id="brandSwitcherDropdown" class="hidden fixed sm:absolute inset-x-3 sm:inset-x-auto top-[68px] sm:top-full sm:right-0 sm:mt-2 w-auto sm:w-72 max-w-sm sm:max-w-none mx-auto sm:mx-0 origin-top-right rounded-2xl border {{ $isFixoraBrand ? 'border-slate-800 bg-slate-900 text-white shadow-2xl shadow-black/70' : 'border-slate-200 bg-white text-slate-900 shadow-2xl shadow-slate-900/15' }} p-2 z-[70]">
                                <div class="px-3 py-2 border-b {{ $isFixoraBrand ? 'border-slate-800' : 'border-slate-100' }}">
                                    <p class="text-[9px] font-extrabold uppercase tracking-wider {{ $isFixoraBrand ? 'text-slate-400' : 'text-slate-400' }}">Active Brand Context</p>
                                    <p class="text-[11px] font-semibold {{ $isFixoraBrand ? 'text-slate-300' : 'text-slate-600' }}">Switch catalogs & formulas</p>
                                </div>

                                <div class="mt-1 space-y-1">
                                    @foreach(availableBrands() as $brand)
                                        @php
                                            $isThisActive = (currentBrand()->id == $brand->id);
                                            $isBrand2 = ($brand->id == 2);
                                        @endphp
                                        <form action="{{ route('brand.switch') }}" method="POST" class="m-0 p-0">
                                            @csrf
                                            <input type="hidden" name="brand_id" value="{{ $brand->id }}">
                                            <button type="submit" class="w-full text-left flex items-center justify-between p-2.5 rounded-xl transition-all duration-150 {{ $isThisActive ? ($isBrand2 ? 'bg-emerald-500/15 border border-emerald-500/40 text-white shadow-sm' : 'bg-orange-50 border border-orange-200 text-orange-950 shadow-sm') : ($isFixoraBrand ? 'hover:bg-white/5 border border-transparent text-slate-300 hover:text-white' : 'hover:bg-slate-50 border border-transparent text-slate-700 hover:text-slate-950') }}">
                                                <div class="flex items-center gap-2.5">
                                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl {{ $isBrand2 ? 'bg-emerald-950/80 border border-emerald-500/30 text-emerald-400 font-black' : 'bg-orange-100/70 border border-orange-200 text-orange-600 font-black' }}">
                                                        <span class="text-xs font-black tracking-tight">{{ $isBrand2 ? 'FX' : 'SC' }}</span>
                                                    </div>
                                                    <div>
                                                        <p class="text-xs font-extrabold leading-tight">{{ $brand->name }}</p>
                                                        <p class="text-[10px] font-medium opacity-70">{{ $isBrand2 ? 'Tiles Adhesive Division' : 'Industrial Adhesives & Chemical' }}</p>
                                                    </div>
                                                </div>

                                                @if($isThisActive)
                                                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full {{ $isBrand2 ? 'bg-emerald-500 text-slate-950' : 'bg-orange-600 text-white' }} shadow">
                                                        <i data-lucide="check" class="h-3.5 w-3.5 stroke-[3]"></i>
                                                    </span>
                                                @endif
                                            </button>
                                        </form>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @elseif(Auth::check())
                        <div class="hidden sm:flex items-center gap-2 {{ $isFixoraBrand ? 'bg-emerald-950/60 border border-emerald-500/30 text-emerald-300' : 'bg-orange-50/80 border border-orange-200 text-orange-800' }} px-3 py-1.5 rounded-2xl mr-1 shrink-0">
                            <i data-lucide="layers" class="h-3.5 w-3.5 {{ $isFixoraBrand ? 'text-emerald-400' : 'text-orange-600' }}"></i>
                            <span class="text-xs font-extrabold">{{ currentBrand()->name }}</span>
                        </div>
                    @endif

                    @if(Auth::check() && Auth::user()->isSupervisor() && availableDepartments()->count() > 1)
                        <form action="{{ route('department.switch') }}" method="POST" id="deptSwitchForm" class="hidden sm:block mr-1 shrink-0">
                            @csrf
                            <div class="flex items-center gap-1.5 {{ $isFixoraBrand ? 'bg-white/5 border border-white/10 text-slate-200 hover:bg-white/10' : 'bg-slate-50 border border-slate-200 text-slate-700 hover:bg-slate-100' }} px-3 py-1.5 rounded-xl transition-all">
                                <i data-lucide="building" class="h-3.5 w-3.5 {{ $isFixoraBrand ? 'text-slate-400' : 'text-slate-500' }}"></i>
                                <select name="department_id" onchange="document.getElementById('deptSwitchForm').submit()" class="bg-transparent border-0 text-xs font-bold {{ $isFixoraBrand ? 'text-slate-200' : 'text-slate-700' }} focus:outline-none cursor-pointer py-0.5">
                                    @foreach(availableDepartments() as $dept)
                                        <option value="{{ $dept->id }}" class="text-slate-900 bg-white" {{ currentDepartment() && currentDepartment()->id == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    @elseif(Auth::check() && Auth::user()->isSupervisor() && currentDepartment())
                        <div class="hidden sm:flex items-center gap-1.5 {{ $isFixoraBrand ? 'bg-white/5 border border-white/10 text-slate-200' : 'bg-slate-50 border border-slate-200 text-slate-700' }} px-3 py-1.5 rounded-xl mr-1 shrink-0">
                            <i data-lucide="building" class="h-3.5 w-3.5 {{ $isFixoraBrand ? 'text-slate-400' : 'text-slate-500' }}"></i>
                            <span class="text-xs font-bold">{{ currentDepartment()->name }}</span>
                        </div>
                    @endif

                    @if(Auth::check() && Auth::user()->isAdmin())
                        @php
                            $maintenanceMode = \App\Models\Setting::get('maintenance_mode', 'off');
                        @endphp
                        <button type="button" id="maintenanceModeToggleBtn"
                                data-status="{{ $maintenanceMode }}"
                                class="relative flex h-9 w-9 sm:h-10 sm:w-10 shrink-0 items-center justify-center rounded-xl border transition focus:outline-none {{ $maintenanceMode === 'on' ? 'text-rose-650 border-rose-200 bg-rose-50 hover:bg-rose-100' : ($isFixoraBrand ? 'text-slate-400 border-white/10 bg-white/5 hover:bg-white/10 hover:text-white' : 'text-slate-500 border-slate-200 bg-white hover:bg-orange-50 hover:text-orange-600 hover:border-orange-200') }}"
                                title="Maintenance Mode Settings">
                            <i data-lucide="wrench" class="h-4 w-4 sm:h-5 sm:w-5"></i>
                            @if($maintenanceMode === 'on')
                                <span class="absolute -right-0.5 -top-0.5 h-2.5 w-2.5 rounded-full bg-rose-600 ring-2 ring-white animate-pulse"></span>
                            @endif
                        </button>
                    @endif

                    <!-- Notification Bell with Dropdown -->
                    <div class="relative shrink-0" id="notifications-dropdown-container">
                        <button type="button" id="bellButton" class="relative flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-xl border {{ $isFixoraBrand ? 'border-white/10 bg-white/5 text-slate-300 hover:bg-emerald-500/20 hover:text-emerald-300 hover:border-emerald-500/30' : 'border-slate-200 bg-white text-slate-500 hover:bg-orange-50 hover:text-orange-600 hover:border-orange-200' }} transition focus:outline-none" aria-label="Notifications">
                            <i data-lucide="bell" class="h-4 w-4 sm:h-5 sm:w-5"></i>
                            <span id="unread-count-badge" class="absolute -right-1 -top-1 h-4 w-4 hidden items-center justify-center rounded-full bg-rose-600 text-[8px] font-black text-white ring-2 ring-white">0</span>
                        </button>

                        <div id="notificationsDropdown" class="hidden fixed sm:absolute inset-x-3 sm:inset-x-auto top-[68px] sm:top-full sm:right-0 sm:mt-2 w-auto sm:w-80 md:w-96 max-w-md sm:max-w-none mx-auto sm:mx-0 flex-col overflow-hidden rounded-2xl border {{ $isFixoraBrand ? 'border-slate-800 bg-slate-900 text-white shadow-2xl shadow-black/70' : 'border-slate-200 bg-white text-slate-900 shadow-2xl shadow-slate-900/15' }} z-[70]">
                            <div class="flex items-center justify-between border-b {{ $isFixoraBrand ? 'border-slate-800 bg-slate-950' : 'border-slate-100 bg-slate-50' }} px-4 py-3">
                                <span class="text-xs font-extrabold uppercase tracking-wider {{ $isFixoraBrand ? 'text-white' : 'text-slate-800' }}">Notifications</span>
                                <button type="button" id="markAllReadBtn" class="text-[10px] font-extrabold {{ $isFixoraBrand ? 'text-emerald-400 hover:text-emerald-300' : 'text-orange-600 hover:text-orange-700' }} hover:underline uppercase tracking-wider">Mark All Read</button>
                            </div>
                            <div id="notificationsList" class="max-h-[300px] sm:max-h-[320px] overflow-y-auto divide-y {{ $isFixoraBrand ? 'divide-slate-800 bg-slate-900' : 'divide-slate-100 bg-white' }}">
                                <div class="p-6 text-center text-xs text-slate-400">Loading...</div>
                            </div>
                            <div class="border-t {{ $isFixoraBrand ? 'border-slate-800 bg-slate-950' : 'border-slate-100 bg-slate-50' }} px-4 py-2.5 text-center">
                                <a href="{{ route('notifications.index') }}" class="text-xs font-bold {{ $isFixoraBrand ? 'text-emerald-400 hover:text-emerald-300' : 'text-orange-600 hover:text-orange-700' }} transition-colors uppercase tracking-wider block">View All Notifications</a>
                            </div>
                        </div>
                    </div>

                    <a href="{{ Auth::user()->isAdmin() ? route('admin.profile.edit') : '#' }}" class="flex shrink-0 items-center gap-2 rounded-xl p-1 sm:p-1.5 transition {{ $isFixoraBrand ? 'hover:bg-white/10' : 'hover:bg-orange-50' }}">
                        @if(Auth::user()->profile_photo)
                            <img src="{{ asset(Auth::user()->profile_photo) }}" class="h-8 w-8 sm:h-9 sm:w-9 rounded-xl object-cover ring-1 {{ $isFixoraBrand ? 'ring-emerald-500/30' : 'ring-orange-200' }}" alt="{{ Auth::user()->name }}">
                        @else
                            <span class="flex h-8 w-8 sm:h-9 sm:w-9 items-center justify-center rounded-xl {{ $isFixoraBrand ? 'bg-gradient-to-tr from-emerald-600 to-teal-500 shadow-emerald-600/30' : 'bg-gradient-to-tr from-orange-600 to-amber-500 shadow-orange-600/30' }} text-xs sm:text-sm font-extrabold text-white shadow-md">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                        @endif
                        <span class="hidden text-left sm:block">
                            <span class="block max-w-[130px] truncate text-xs font-bold {{ $isFixoraBrand ? 'text-white' : 'text-slate-800' }}">{{ Auth::user()->name }}</span>
                            <span class="block text-[10px] font-extrabold capitalize {{ $isFixoraBrand ? 'text-emerald-400' : 'text-orange-600' }}">{{ Auth::user()->roles->first()?->name ?? 'User' }}</span>
                        </span>
                    </a>
                </div>
            </header>

            <main class="page-content px-4 py-5 pb-28 sm:px-6 sm:py-7 lg:px-8 lg:pb-10">
                @if(session('brand_switched'))
                    <div id="brandSwitchToast" class="fixed top-5 right-5 z-[9999] max-w-sm w-full transition-all duration-300 transform translate-y-0 opacity-100">
                        <div class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white p-3.5 shadow-xl shadow-slate-900/10">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-700 border border-slate-200/80">
                                <i data-lucide="tag" class="h-4 w-4 text-slate-700"></i>
                            </div>
                            
                            <div class="flex-1 min-w-0">
                                <div class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Active Brand Context</div>
                                <div class="text-xs font-bold text-slate-800 truncate">
                                    Switched to <span class="text-slate-950 font-extrabold">{{ session('brand_switched') }}</span>
                                </div>
                            </div>

                            <button type="button" onclick="dismissBrandToast()" class="shrink-0 p-1 text-slate-400 hover:text-slate-700 rounded-lg hover:bg-slate-100 transition" aria-label="Close notification">
                                <i data-lucide="x" class="h-4 w-4"></i>
                            </button>
                        </div>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            setTimeout(function() {
                                dismissBrandToast();
                            }, 3500);
                        });

                        function dismissBrandToast() {
                            var toast = document.getElementById('brandSwitchToast');
                            if (toast) {
                                toast.classList.add('opacity-0', '-translate-y-2');
                                setTimeout(function() {
                                    toast.remove();
                                }, 300);
                            }
                        }
                    </script>
                @endif

                @if(session('success'))
                    <div class="flash-message mb-5 flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-800 shadow-sm">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600"><i data-lucide="check-circle" class="h-5 w-5"></i></span>
                        <div class="pt-1.5">{{ session('success') }}</div>
                    </div>
                @endif
                @if(session('error'))
                    <div class="flash-message mb-5 flex items-start gap-3 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm font-semibold text-rose-800 shadow-sm">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-rose-100 text-rose-600"><i data-lucide="alert-triangle" class="h-5 w-5"></i></span>
                        <div class="pt-1.5">{{ session('error') }}</div>
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>

    @if(Auth::check() && Auth::user()->isMarketing())
        <nav class="mobile-dock fixed inset-x-3 bottom-3 z-50 grid grid-cols-1 rounded-[22px] {{ $isFixoraBrand ? 'border border-emerald-500/25 bg-slate-950/95 shadow-black/70 text-slate-300' : 'border border-orange-100 bg-white/95 shadow-slate-900/15 text-slate-700' }} px-2 pb-[max(.45rem,env(safe-area-inset-bottom))] pt-2 shadow-2xl backdrop-blur-xl lg:hidden" aria-label="Mobile navigation">
            <a href="{{ route('marketing.orders.index') }}" class="mobile-nav-item {{ request()->routeIs('marketing.orders.*') ? 'is-active' : '' }}"><i data-lucide="clipboard-list"></i><span>Orders Board</span></a>
        </nav>
    @elseif(Auth::check() && Auth::user()->isDispatch())
        <nav class="mobile-dock fixed inset-x-3 bottom-3 z-50 grid grid-cols-2 rounded-[22px] {{ $isFixoraBrand ? 'border border-emerald-500/25 bg-slate-950/95 shadow-black/70 text-slate-300' : 'border border-orange-100 bg-white/95 shadow-slate-900/15 text-slate-700' }} px-2 pb-[max(.45rem,env(safe-area-inset-bottom))] pt-2 shadow-2xl backdrop-blur-xl lg:hidden" aria-label="Mobile navigation">
            <a href="{{ route('dispatch.index') }}" class="mobile-nav-item {{ request()->routeIs('dispatch.index') || request()->routeIs('dispatch.show') || request()->routeIs('dispatch.loading') ? 'is-active' : '' }}"><i data-lucide="truck"></i><span>Dispatches</span></a>
            <a href="{{ route('dispatch.reports') }}" class="mobile-nav-item {{ request()->routeIs('dispatch.reports') ? 'is-active' : '' }}"><i data-lucide="bar-chart-3"></i><span>Reports</span></a>
        </nav>
    @else
        <nav class="mobile-dock fixed inset-x-3 bottom-3 z-50 grid grid-cols-5 rounded-[22px] {{ $isFixoraBrand ? 'border border-emerald-500/25 bg-slate-950/95 shadow-black/70 text-slate-300' : 'border border-orange-100 bg-white/95 shadow-slate-900/15 text-slate-700' }} px-2 pb-[max(.45rem,env(safe-area-inset-bottom))] pt-2 shadow-2xl backdrop-blur-xl lg:hidden" aria-label="Mobile navigation">
            @php
                $mobileDashboard = Auth::user()->isAdmin() ? route('admin.dashboard') : route('supervisor.dashboard');
                $settingsRoute = Auth::user()->isAdmin() ? route('admin.settings.factory') : '#';
                $settingsClass = Auth::user()->isAdmin() ? '' : 'coming-soon-link';
            @endphp
            <a href="{{ $mobileDashboard }}" class="mobile-nav-item {{ request()->routeIs('admin.dashboard') || request()->routeIs('supervisor.dashboard') ? 'is-active' : '' }}"><i data-lucide="layout-dashboard"></i><span>Dashboard</span></a>
            <a href="{{ route('production.index') }}" class="mobile-nav-item {{ request()->routeIs('production.index') || request()->routeIs('grout-production.index') || request()->routeIs('production.show') || request()->routeIs('grout-production.running') ? 'is-active' : '' }}"><i data-lucide="activity"></i><span>Production</span></a>
            <a href="{{ route('production.ledger') }}" class="mobile-nav-item {{ request()->routeIs('production.ledger') ? 'is-active' : '' }}"><i data-lucide="archive"></i><span>Stock</span></a>
            <a href="{{ route('admin.reports.daily') }}" class="mobile-nav-item {{ request()->routeIs('admin.reports.*') ? 'is-active' : '' }}"><i data-lucide="bar-chart-3"></i><span>Reports</span></a>
            <a href="{{ $settingsRoute }}" class="mobile-nav-item {{ $settingsClass }} {{ request()->routeIs('admin.settings.*') ? 'is-active' : '' }}"><i data-lucide="settings"></i><span>Settings</span></a>
        </nav>
    @endif



    <script>
        window.formatQuantity = function(val, maxDecimals = 4) {
            if (val === null || val === undefined || val === '') return '0';
            let num = parseFloat(val);
            if (isNaN(num)) return '0';
            let str = num.toFixed(maxDecimals);
            if (str.indexOf('.') !== -1) {
                str = str.replace(/\.?0+$/, '');
            }
            return str;
        };

        window.heroiconMap = {
            'layout-dashboard': 'squares-2x2',
            'panel-left-close': 'bars-3-bottom-left',
            'cpu': 'cpu-chip',
            'scale': 'scale',
            'package': 'archive-box',
            'box': 'cube',
            'award': 'trophy',
            'file-spreadsheet': 'document-chart-bar',
            'history': 'clock',
            'book-open': 'book-open',
            'file-text': 'document-text',
            'settings': 'cog-6-tooth',
            'clipboard-list': 'clipboard-document-list',
            'database': 'circle-stack',
            'heart-pulse': 'heart',
            'bar-chart-3': 'chart-bar',
            'trending-up': 'arrow-trending-up',
            'pie-chart': 'chart-pie',
            'line-chart': 'presentation-chart-line',
            'alert-triangle': 'exclamation-triangle',
            'alert-circle': 'exclamation-circle',
            'check-circle': 'check-circle',
            'check-circle-2': 'check-circle',
            'bell': 'bell',
            'search': 'magnifying-glass',
            'log-out': 'arrow-right-start-on-rectangle',
            'activity': 'signal',
            'archive': 'archive-box',
            'plus': 'plus',
            'play': 'play',
            'layers': 'square-3-stack-3d',
            'refresh-cw': 'arrow-path',
            'calendar-days': 'calendar-days',
            'chevron-right': 'chevron-right',
            'mouse-pointer-click': 'cursor-arrow-rays',
            'shield-check': 'shield-check',
            'loader': 'arrow-path',
            'coffee': 'pause-circle',
            'package-x': 'archive-box-x-mark',
            'eye': 'eye',
            'filter': 'funnel',
            'x': 'x-mark',
            'info': 'information-circle',
            'building-2': 'building-office-2',
            'check': 'check',
            'clock': 'clock',
            'download': 'arrow-down-tray',
            'upload': 'arrow-up-tray',
            'menu': 'bars-3',
            'user': 'user-circle',
            'users': 'users',
            'edit': 'pencil-square',
            'trash': 'trash',
            'save': 'check',
            'arrow-left': 'arrow-left',
            'external-link': 'arrow-top-right-on-square',
            'arrow-right': 'arrow-right',
            'building': 'building-office',
            'calendar': 'calendar',
            'camera': 'camera',
            'clipboard': 'clipboard',
            'contact': 'identification',
            'database-backup': 'circle-stack',
            'download-cloud': 'cloud-arrow-down',
            'edit-2': 'pencil-square',
            'file-code': 'document-text',
            'folder-open': 'folder-open',
            'git-commit': 'minus-circle',
            'hard-drive': 'server',
            'image': 'photo',
            'key-round': 'key',
            'lock': 'lock-closed',
            'mail': 'envelope',
            'message-square': 'chat-bubble-left-right',
            'milestone': 'map-pin',
            'monitor': 'computer-desktop',
            'package-check': 'archive-box',
            'palette': 'paint-brush',
            'plus-circle': 'plus-circle',
            'printer': 'printer',
            'rotate-ccw': 'arrow-path',
            'send': 'paper-airplane',
            'sheet': 'document-text',
            'sliders': 'adjustments-vertical',
            'sparkles': 'sparkles',
            'toggle-left': 'minus-circle',
            'trash-2': 'trash',
            'upload-cloud': 'cloud-arrow-up',
            'zap': 'bolt',
            'wrench': 'wrench',
            'shield-exclamation': 'shield-exclamation'
        };
        window.renderHeroicons = function(root = document) {
            const nodes = [];
            if (root && root.hasAttribute && root.hasAttribute('data-lucide')) {
                nodes.push(root);
            }
            if (root && root.querySelectorAll) {
                root.querySelectorAll('[data-lucide]').forEach(function(node) {
                    nodes.push(node);
                });
            }
            nodes.forEach(function(node) {
                if (node.tagName.toLowerCase() === 'iconify-icon') return;
                const name = window.heroiconMap[node.dataset.lucide] || node.dataset.lucide || 'square-2-stack';
                const icon = document.createElement('iconify-icon');
                icon.setAttribute('icon', 'heroicons:' + name);
                icon.setAttribute('aria-hidden', 'true');
                icon.setAttribute('width', '100%');
                icon.setAttribute('height', '100%');
                icon.className = node.className;
                node.replaceWith(icon);
            });
        };
        window.lucide = { createIcons: function(){ window.renderHeroicons(); } };
        document.addEventListener('DOMContentLoaded', function(){
            window.renderHeroicons();

            function escapeHtml(text) {
                return text ? $('<div>').text(text).html() : '';
            }

            window.fetchUnreadNotifications = function() {
                $.getJSON('/notifications/unread', function(data) {
                    const count = data.unread_count;
                    const badge = $('#unread-count-badge');
                    if (count > 0) {
                        badge.text(count).removeClass('hidden').addClass('flex');
                    } else {
                        badge.addClass('hidden').removeClass('flex');
                    }

                    const list = $('#notificationsList');
                    list.empty();
                    if (data.notifications.length === 0) {
                        list.append('<div class="p-6 text-center text-xs text-slate-400">No notifications found</div>');
                        return;
                    }

                    data.notifications.forEach(function(item) {
                        const readClass = item.is_read ? 'bg-white opacity-60' : 'bg-blue-50/10 font-semibold';
                        const unreadIndicator = item.is_read ? '' : '<span class="h-1.5 w-1.5 shrink-0 rounded-full bg-blue-600"></span>';

                        const notifHtml = `
                            <div class="p-3.5 flex items-start gap-3 hover:bg-slate-50 transition-colors text-xs ${readClass}">
                                <div class="flex-1 space-y-0.5 min-w-0">
                                    <a href="${item.click_url}" class="block font-bold text-slate-800 hover:underline notification-item-link truncate" data-id="${item.id}">${escapeHtml(item.title)}</a>
                                    <p class="text-slate-500 line-clamp-2 leading-relaxed">${escapeHtml(item.body)}</p>
                                    <span class="block text-[10px] text-slate-400 font-medium">${item.created_at_human}</span>
                                </div>
                                <div class="flex flex-col items-center gap-2 shrink-0">
                                    ${unreadIndicator}
                                    ${!item.is_read ? `<button type="button" class="mark-read-btn text-[10px] text-blue-650 hover:text-blue-800 font-bold uppercase tracking-wider cursor-pointer" data-id="${item.id}">Read</button>` : ''}
                                </div>
                            </div>
                        `;
                        list.append(notifHtml);
                    });
                });
            };

            $('#bellButton').click(function(e) {
                e.stopPropagation();
                $('#brandSwitcherDropdown').addClass('hidden');
                $('#brandChevronIcon').removeClass('rotate-180');
                $('#notificationsDropdown').toggleClass('hidden');
                window.fetchUnreadNotifications();
            });

            // Brand Switcher Dropdown Toggle
            $('#brandSwitcherBtn').click(function(e) {
                e.stopPropagation();
                const dropdown = $('#brandSwitcherDropdown');
                const isHidden = dropdown.hasClass('hidden');
                $('#notificationsDropdown').addClass('hidden');
                dropdown.toggleClass('hidden');
                $('#brandChevronIcon').toggleClass('rotate-180', isHidden);
            });

            $(document).click(function(e) {
                if (!$(e.target).closest('#notifications-dropdown-container').length) {
                    $('#notificationsDropdown').addClass('hidden');
                }
                if (!$(e.target).closest('#brandSwitcherContainer').length) {
                    $('#brandSwitcherDropdown').addClass('hidden');
                    $('#brandChevronIcon').removeClass('rotate-180');
                }
            });

            $('#markAllReadBtn').click(function() {
                $.post('/notifications/read-all', {
                    _token: $('meta[name="csrf-token"]').attr('content')
                }, function() {
                    window.fetchUnreadNotifications();
                });
            });

            $(document).on('click', '.mark-read-btn', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const id = $(this).data('id');
                $.post(`/notifications/${id}/read`, {
                    _token: $('meta[name="csrf-token"]').attr('content')
                }, function() {
                    window.fetchUnreadNotifications();
                });
            });

            $(document).on('click', '.notification-item-link', function(e) {
                const id = $(this).data('id');
                const href = $(this).attr('href');
                if (href && href !== '#') {
                    e.preventDefault();
                    $.post(`/notifications/${id}/read`, {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    }, function() {
                        window.location.href = href;
                    });
                }
            });

            // Initial fetch
            window.fetchUnreadNotifications();
            window.setInterval(window.fetchUnreadNotifications, 30000);

            // Maintenance Mode Control Panel
            $('#maintenanceModeToggleBtn').click(function() {
                const btn = $(this);
                const currentStatus = btn.data('status');

                Swal.fire({
                    title: 'System Maintenance Control',
                    html: `
                        <div class="text-left font-sans space-y-4">
                            <p class="text-xs text-slate-500 mb-4">Manage system access. Active maintenance blocks all non-admin users.</p>

                            <div class="flex items-center justify-between p-3.5 bg-slate-50 rounded-xl border border-slate-200">
                                <div>
                                    <span class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider">Maintenance Mode</span>
                                    <span class="text-xs font-bold text-slate-700" id="swal-status-text">
                                        ${currentStatus === 'on' ? '🟢 Active (System Blocked)' : '🔴 Inactive (System Open)'}
                                    </span>
                                </div>
                                <button type="button" id="swal-toggle-status-btn" class="px-3 py-1.5 text-xs font-extrabold rounded-lg shadow-sm text-white transition-all cursor-pointer ${currentStatus === 'on' ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-rose-600 hover:bg-rose-700'}">
                                    ${currentStatus === 'on' ? 'Deactivate' : 'Activate'}
                                </button>
                            </div>

                            <div>
                                <label for="swal-unlock-password" class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Set / Update Unlock Password</label>
                                <div class="relative">
                                    <input type="password" id="swal-unlock-password" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" placeholder="••••••••">
                                </div>
                                <p class="text-[10px] text-slate-400 mt-1">Leave blank to keep the current password.</p>
                            </div>
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Save Password Changes',
                    cancelButtonText: 'Close Panel',
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-sm cursor-pointer',
                        cancelButton: 'px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl shadow-sm cursor-pointer'
                    },
                    buttonsStyling: false,
                    didOpen: () => {
                        let tempStatus = currentStatus;
                        $('#swal-toggle-status-btn').click(function() {
                            tempStatus = tempStatus === 'on' ? 'off' : 'on';

                            // Ask for confirmation
                            Swal.fire({
                                title: tempStatus === 'on' ? 'Activate Maintenance Mode?' : 'Deactivate Maintenance Mode?',
                                text: tempStatus === 'on'
                                    ? 'This will block all non-admin users and show the maintenance screen.'
                                    : 'This will restore public access to the ERP.',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Yes, Proceed',
                                cancelButtonText: 'Cancel',
                                customClass: {
                                    confirmButton: 'px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl mr-2',
                                    cancelButton: 'px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-750 font-bold text-xs rounded-xl'
                                },
                                buttonsStyling: false
                            }).then((conf) => {
                                if (conf.isConfirmed) {
                                    // Send AJAX request to update status
                                    $.post('/admin/maintenance/update', {
                                        _token: $('meta[name="csrf-token"]').attr('content'),
                                        maintenance_mode: tempStatus,
                                        unlock_password: ''
                                    })
                                    .done(function(res) {
                                        Swal.fire({
                                            title: 'Updated!',
                                            text: res.message,
                                            icon: 'success',
                                            timer: 1500,
                                            showConfirmButton: false
                                        }).then(() => {
                                            window.location.reload();
                                        });
                                    })
                                    .fail(function(xhr) {
                                        const msg = xhr.responseJSON && xhr.responseJSON.message
                                            ? xhr.responseJSON.message
                                            : (xhr.responseJSON && xhr.responseJSON.errors && xhr.responseJSON.errors.unlock_password
                                                ? xhr.responseJSON.errors.unlock_password[0]
                                                : 'Failed to update settings.');

                                        Swal.fire('Error', msg, 'error');
                                        tempStatus = tempStatus === 'on' ? 'off' : 'on'; // revert tempStatus
                                    });
                                } else {
                                    tempStatus = tempStatus === 'on' ? 'off' : 'on'; // revert tempStatus
                                }
                            });
                        });
                    },
                    preConfirm: () => {
                        return $('#swal-unlock-password').val();
                    }
                }).then((res) => {
                    if (res.isConfirmed && res.value) {
                        const newPassword = res.value;
                        if (newPassword.length < 4) {
                            Swal.fire('Error', 'Unlock password must be at least 4 characters long.', 'error');
                            return;
                        }

                        $.post('/admin/maintenance/update', {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            maintenance_mode: currentStatus,
                            unlock_password: newPassword
                        })
                        .done(function(resp) {
                            Swal.fire({
                                title: 'Saved!',
                                text: 'Unlock password has been updated securely.',
                                icon: 'success',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        })
                        .fail(function(xhr) {
                            Swal.fire('Error', 'Failed to update password.', 'error');
                        });
                    }
                });
            });
        });
    </script>
    @yield('scripts')
    @include('partials.pwa')
</body>
</html>
