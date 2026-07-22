@php
$user = Auth::user();
$homeRoute = $user->isAdmin() ? route('admin.dashboard') : route('supervisor.dashboard');
$navClass = fn($active) => $active ? 'sidebar-link is-active' : 'sidebar-link';

// Resolve dynamic department access permissions
$deptTAD = \App\Models\Department::where('code', 'TAD')->first();
$deptGRT = \App\Models\Department::where('code', 'GRT')->first();
$deptEPX = \App\Models\Department::where('code', 'EPX')->first();

$canAccessAdhesive = !$user->isMarketing() && !$user->isDispatch() && ($user->isAdmin() || ($deptTAD && $user->canAccessDepartment($deptTAD->id)));
$canAccessGrout = !$user->isMarketing() && !$user->isDispatch() && ($user->isAdmin() || ($deptGRT && $user->canAccessDepartment($deptGRT->id)));
$canAccessEpoxy = !$user->isMarketing() && !$user->isDispatch() && ($user->isAdmin() || ($deptEPX && $user->canAccessDepartment($deptEPX->id)));
$canAccessMarketing = $user->isAdmin() || $user->isSupervisor() || $user->isMarketing();
$canAccessDispatch = $user->isAdmin() || $user->isMarketing() || $user->isDispatch() || $user->isSupervisor();
@endphp

<aside id="appSidebar"
    class="app-sidebar fixed inset-y-3 left-3 z-50 hidden w-[264px] flex-col overflow-hidden rounded-[24px] bg-slate-950 text-white shadow-2xl shadow-slate-950/20 transition-all duration-300 lg:flex">
    <div class="flex h-[72px] items-center gap-3 border-b border-white/10 px-5">
        <a href="{{ $homeRoute }}" class="flex min-w-0 items-center gap-3">
            <span
                class="brand-mark flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-blue-600 text-sm font-black tracking-tight text-white shadow-lg shadow-blue-600/25">SI</span>
            <span class="sidebar-label min-w-0"><span
                    class="block text-[15px] font-extrabold tracking-[0.18em]">SOLCON</span><span
                    class="block truncate text-[9px] font-semibold uppercase tracking-[0.16em] text-slate-500">Factory
                    OS</span></span>
        </a>
        <span
            class="sidebar-label ml-auto inline-flex items-center gap-1.5 rounded-full border border-emerald-400/20 bg-emerald-400/10 px-2 py-1 text-[9px] font-bold text-emerald-300"><span
                class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span>Live</span>
    </div>

    <nav class="sidebar-scroll flex-1 space-y-6 overflow-y-auto px-3 py-5">
        {{-- General Section --}}
        @if(!$user->isMarketing() && !$user->isDispatch())
        <section>
            <p class="sidebar-label nav-eyebrow">General</p>
            <div class="space-y-1">
                <a href="{{ $homeRoute }}"
                    class="{{ $navClass(request()->routeIs('admin.dashboard') || request()->routeIs('supervisor.dashboard')) }}"
                    title="Dashboard">
                    <i data-lucide="layout-dashboard"></i>
                    <span class="sidebar-label">Dashboard</span>
                </a>
            </div>
        </section>
        @endif

        {{-- Marketing Section --}}
        @if($canAccessMarketing)
        <section>
            <p class="sidebar-label nav-eyebrow">Marketing</p>
            <div class="space-y-1">
                @if(!$user->isSupervisor())
                <a href="{{ route('marketing.orders.index') }}"
                    class="{{ $navClass(request()->routeIs('marketing.orders.*')) }}"
                    title="Orders Board">
                    <i data-lucide="clipboard-list"></i>
                    <span class="sidebar-label">Orders Board</span>
                </a>
                @endif
                @if($user->isSupervisor() || $user->isAdmin())
                <a href="{{ route('supervisor.orders') }}"
                    class="{{ $navClass(request()->routeIs('supervisor.orders')) }}"
                    title="Approved Orders">
                    <i data-lucide="check-circle"></i>
                    <span class="sidebar-label">Approved Orders</span>
                </a>
                @endif
            </div>
        </section>
        @endif

        {{-- Dispatch Section --}}
        @if($canAccessDispatch)
        <section>
            <p class="sidebar-label nav-eyebrow">Dispatch Dept</p>
            <div class="space-y-1">
                <a href="{{ route('dispatch.index') }}"
                    class="{{ $navClass(request()->routeIs('dispatch.index') || request()->routeIs('dispatch.show') || request()->routeIs('dispatch.loading')) }}"
                    title="Dispatch Planning">
                    <i data-lucide="truck"></i>
                    <span class="sidebar-label">Dispatches</span>
                </a>
                @if($user->isAdmin() || $user->isMarketing())
                <a href="{{ route('dispatch.create') }}"
                    class="{{ $navClass(request()->routeIs('dispatch.create')) }}"
                    title="Create Dispatch">
                    <i data-lucide="plus-circle"></i>
                    <span class="sidebar-label">Create Dispatch</span>
                </a>
                @endif
                <a href="{{ route('dispatch.reports') }}"
                    class="{{ $navClass(request()->routeIs('dispatch.reports')) }}"
                    title="Dispatch Reports">
                    <i data-lucide="bar-chart"></i>
                    <span class="sidebar-label">Dispatch Reports</span>
                </a>
            </div>
        </section>
        @endif

        {{-- Adhesive Section --}}
        @if($canAccessAdhesive)
        <section>
            <p class="sidebar-label nav-eyebrow">Adhesive Dept</p>
            <div class="space-y-1">
                <a href="{{ route('production.running_active') }}"
                    class="{{ $navClass(request()->routeIs('production.index') || request()->routeIs('production.running_active') || request()->routeIs('production.show')) }}"
                    title="Live production">
                    <i data-lucide="activity"></i>
                    <span class="sidebar-label">Live Production</span>
                    <span
                        class="sidebar-label ml-auto h-2 w-2 rounded-full bg-emerald-400 shadow-[0_0_0_4px_rgba(52,211,153,.1)]"></span>
                </a>
                <a href="{{ route('production.create') }}"
                    class="{{ $navClass(request()->routeIs('production.create')) }}" title="Start batch">
                    <i data-lucide="play"></i>
                    <span class="sidebar-label">Start Batch</span>
                </a>
                <a href="{{ route('production.history') }}"
                    class="{{ $navClass(request()->routeIs('production.history')) }}"
                    title="Production history">
                    <i data-lucide="history"></i>
                    <span class="sidebar-label">Production History</span>
                </a>
            </div>
        </section>
        @endif

        {{-- Grout Section --}}
        @if($canAccessGrout)
        <section>
            <p class="sidebar-label nav-eyebrow">Grout Dept</p>
            <div class="space-y-1">
                <a href="{{ route('grout-production.index') }}"
                    class="{{ $navClass(request()->routeIs('grout-production.index') || request()->routeIs('grout-production.show') || request()->routeIs('grout-production.running')) }}"
                    title="Grout Floor">
                    <i data-lucide="activity"></i>
                    <span class="sidebar-label">Grout Floor</span>
                </a>
                <a href="{{ route('grout-production.create') }}"
                    class="{{ $navClass(request()->routeIs('grout-production.create')) }}" title="Start Grout Run">
                    <i data-lucide="plus-circle"></i>
                    <span class="sidebar-label">Start Grout Batch</span>
                </a>
            </div>
        </section>
        @endif

        {{-- Epoxy Section --}}
        @if($canAccessEpoxy)
        <section>
            <p class="sidebar-label nav-eyebrow">Epoxy Dept</p>
            <div class="space-y-1">
                <a href="{{ route('epoxy.index') }}"
                    class="{{ $navClass(request()->routeIs('epoxy.index')) }}"
                    title="Epoxy Floor">
                    <i data-lucide="activity"></i>
                    <span class="sidebar-label">Epoxy Floor</span>
                </a>
                <a href="{{ route('epoxy.component-entry') }}"
                    class="{{ $navClass(request()->routeIs('epoxy.component-entry')) }}"
                    title="Component Entry">
                    <i data-lucide="plus-circle"></i>
                    <span class="sidebar-label">Component Entry</span>
                </a>
                <a href="{{ route('epoxy.bucket-assembly') }}"
                    class="{{ $navClass(request()->routeIs('epoxy.bucket-assembly')) }}"
                    title="Bucket Assembly">
                    <i data-lucide="package"></i>
                    <span class="sidebar-label">Bucket Assembly</span>
                </a>
            </div>
        </section>
        @endif

        {{-- Inventory Section --}}
        @if(!$user->isMarketing() && !$user->isDispatch())
        <section>
            <p class="sidebar-label nav-eyebrow">Inventory</p>
            <div class="space-y-1">
                @if($user->isAdmin())
                <a href="{{ route('admin.raw-materials.index') }}"
                    class="{{ $navClass(request()->routeIs('admin.raw-materials.*')) }}" title="Raw Materials">
                    <i data-lucide="box"></i>
                    <span class="sidebar-label">Raw Materials</span>
                </a>
                @endif
                <a href="{{ route('finished-goods.index') }}"
                    class="{{ $navClass(request()->routeIs('finished-goods.*')) }}" title="Finished Goods">
                    <i data-lucide="archive"></i>
                    <span class="sidebar-label">Finished Goods</span>
                </a>
                <a href="{{ route('production.ledger') }}"
                    class="{{ $navClass(request()->routeIs('production.ledger')) }}" title="Stock Ledger">
                    <i data-lucide="file-text"></i>
                    <span class="sidebar-label">Stock Ledger</span>
                </a>
                <a href="{{ route('admin.stock-adjustments.index') }}"
                    class="{{ $navClass(request()->routeIs('admin.stock-adjustments.*')) }}" title="Stock IN / OUT">
                    <i data-lucide="sliders"></i>
                    <span class="sidebar-label">Stock IN / OUT</span>
                </a>
            </div>
        </section>
        @endif

        {{-- Operations / Reports Section --}}
        @if(($user->isAdmin() || $user->hasPermission('view-reports')) && !$user->isMarketing() && !$user->isDispatch())
        <section>
            <p class="sidebar-label nav-eyebrow">Reports</p>
            <div class="space-y-1">
                <a href="{{ route('admin.reports.daily') }}"
                    class="{{ $navClass(request()->routeIs('admin.reports.*')) }}" title="Daily Reports">
                    <i data-lucide="bar-chart-3"></i>
                    <span class="sidebar-label">Daily Reports</span>
                </a>
            </div>
        </section>
        @endif

        {{-- Admin Configuration Sections --}}
        @if($user->isAdmin())
        <section>
            <p class="sidebar-label nav-eyebrow">Configuration</p>
            <div class="space-y-1">
                {{-- Global Setup --}}
                <details class="sidebar-more" {{ request()->routeIs('admin.departments.*') ||
                    request()->routeIs('admin.machines.*') || request()->routeIs('admin.units.*') ? 'open' : '' }}>
                    <summary class="sidebar-link cursor-pointer">
                        <i data-lucide="globe"></i>
                        <span class="sidebar-label">Global Setup</span>
                        <i data-lucide="chevron-right" class="sidebar-label ml-auto h-4 w-4 transition-transform"></i>
                    </summary>
                    <div class="sidebar-label ml-9 mt-1 space-y-1 border-l border-white/10 pl-3">
                        <a href="{{ route('admin.departments.index') }}"
                            class="sub-link {{ request()->routeIs('admin.departments.*') ? 'is-active' : '' }}">Departments</a>
                        <a href="{{ route('admin.machines.index') }}"
                            class="sub-link {{ request()->routeIs('admin.machines.*') ? 'is-active' : '' }}">Machines</a>
                        <a href="{{ route('admin.units.index') }}"
                            class="sub-link {{ request()->routeIs('admin.units.*') ? 'is-active' : '' }}">Units</a>
                    </div>
                </details>

                {{-- Adhesive Setup --}}
                <details class="sidebar-more" {{ request()->routeIs('admin.grades.*') ||
                    request()->routeIs('admin.formulas.*') || request()->routeIs('admin.bag-sizes.*') ? 'open' : '' }}>
                    <summary class="sidebar-link cursor-pointer">
                        <i data-lucide="settings"></i>
                        <span class="sidebar-label">Adhesive Setup</span>
                        <i data-lucide="chevron-right" class="sidebar-label ml-auto h-4 w-4 transition-transform"></i>
                    </summary>
                    <div class="sidebar-label ml-9 mt-1 space-y-1 border-l border-white/10 pl-3">
                        <a href="{{ route('admin.grades.index') }}"
                            class="sub-link {{ request()->routeIs('admin.grades.*') ? 'is-active' : '' }}">Grades</a>
                        <a href="{{ route('admin.formulas.index') }}"
                            class="sub-link {{ request()->routeIs('admin.formulas.*') ? 'is-active' : '' }}">Formulas</a>
                        <a href="{{ route('admin.bag-sizes.index') }}"
                            class="sub-link {{ request()->routeIs('admin.bag-sizes.*') ? 'is-active' : '' }}">Bag Sizes</a>
                    </div>
                </details>

                {{-- Grout Setup --}}
                <details class="sidebar-more" {{ request()->routeIs('admin.grout-colors.*') ||
                    request()->routeIs('admin.gro   rmulas.*') ? 'open' : '' }}>
                    <summary class="sidebar-link cursor-pointer">
                        <i data-lucide="palette"></i>
                        <span class="sidebar-label">Grout Setup</span>
                        <i data-lucide="chevron-right" class="sidebar-label ml-auto h-4 w-4 transition-transform"></i>
                    </summary>
                    <div class="sidebar-label ml-9 mt-1 space-y-1 border-l border-white/10 pl-3">
                        <a href="{{ route('admin.grout-colors.index') }}"
                            class="sub-link {{ request()->routeIs('admin.grout-colors.*') ? 'is-active' : '' }}">Grout Colors</a>
                        <a href="{{ route('admin.grout-formulas.index') }}"
                            class="sub-link {{ request()->routeIs('admin.grout-formulas.*') ? 'is-active' : '' }}">Grout Formulas</a>
                    </div>
                </details>

                {{-- Epoxy Setup --}}
                <details class="sidebar-more" {{ request()->routeIs('admin.epoxy-products.*') ||
                    request()->routeIs('admin.epoxy-formulas.*') || request()->routeIs('admin.epoxy-colors.*') ||
                    request()->routeIs('admin.epoxy-components.*') || request()->routeIs('admin.epoxy-component-formulas.*') ? 'open' : '' }}>
                    <summary class="sidebar-link cursor-pointer">
                        <i data-lucide="package"></i>
                        <span class="sidebar-label">Epoxy Setup</span>
                        <i data-lucide="chevron-right" class="sidebar-label ml-auto h-4 w-4 transition-transform"></i>
                    </summary>
                    <div class="sidebar-label ml-9 mt-1 space-y-1 border-l border-white/10 pl-3">
                        <a href="{{ route('admin.epoxy-products.index') }}"
                            class="sub-link {{ request()->routeIs('admin.epoxy-products.*') ? 'is-active' : '' }}">Epoxy Products</a>
                        <a href="{{ route('admin.epoxy-formulas.index') }}"
                            class="sub-link {{ request()->routeIs('admin.epoxy-formulas.*') ? 'is-active' : '' }}">Epoxy Formulas</a>
                        <a href="{{ route('admin.epoxy-colors.index') }}"
                            class="sub-link {{ request()->routeIs('admin.epoxy-colors.*') ? 'is-active' : '' }}">Epoxy Colors</a>
                        <a href="{{ route('admin.epoxy-components.index') }}"
                            class="sub-link {{ request()->routeIs('admin.epoxy-components.*') ? 'is-active' : '' }}">Epoxy Components</a>
                        <a href="{{ route('admin.epoxy-component-formulas.index') }}"
                            class="sub-link {{ request()->routeIs('admin.epoxy-component-formulas.*') ? 'is-active' : '' }}">Component Formulas</a>
                    </div>
                </details>
            </div>
        </section>

        {{-- System Section --}}
        <section>
            <p class="sidebar-label nav-eyebrow">System</p>
            <div class="space-y-1">
                <a href="{{ route('admin.users.index') }}" class="{{ $navClass(request()->routeIs('admin.users.*')) }}"
                    title="User management"><i data-lucide="users"></i><span class="sidebar-label">Users</span></a>
                <a href="{{ route('admin.settings.factory') }}"
                    class="{{ $navClass(request()->routeIs('admin.settings.*')) }}"><i data-lucide="settings"></i><span
                        class="sidebar-label">Settings</span></a>
                <a href="{{ route('admin.activity-logs') }}"
                    class="{{ $navClass(request()->routeIs('admin.activity-logs*')) }}"><i
                        data-lucide="clipboard-list"></i><span class="sidebar-label">Activity Log</span></a>
            </div>
        </section>
        @endif
    </nav>

    <div class="border-t border-white/10 p-3">
        <div class="flex items-center gap-3 rounded-2xl bg-white/[.06] p-2.5">
            <span
                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white/10 text-sm font-extrabold">{{
                strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
            <span class="sidebar-label min-w-0 flex-1"><span class="block truncate text-xs font-bold">{{
                    Auth::user()->name }}</span><span class="block truncate text-[10px] capitalize text-slate-500">{{
                    Auth::user()->roles->first()?->name }}</span></span>
            <form method="POST" action="{{ route('logout') }}" class="sidebar-label">@csrf<button type="submit"
                    class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-500 hover:bg-white/10 hover:text-white"
                    title="Log out"><i data-lucide="log-out"></i></button></form>
        </div>
    </div>
</aside>
