<div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-3">
    @forelse($liveMachines as $machine)
        @php
            $statusLower = strtolower($machine['status']);
            $isGrout = isset($machine['department_code']) && $machine['department_code'] === 'GRT';
            $running = $statusLower !== 'idle' && $statusLower !== 'completed';
            $completed = $statusLower === 'completed';
            
            // Route definitions
            $createRoute = $isGrout ? route('grout-production.create') : route('production.create');
            $activeRoute = $isGrout ? route('grout-production.running', $machine['batch_id'] ?? 0) : route('production.show', $machine['batch_id'] ?? 0);
            if ($completed && $isGrout) {
                $activeRoute = route('grout-production.show', $machine['batch_id'] ?? 0);
            }

            // High-fidelity border/background coloring
            $borderClass = 'border-slate-200 bg-white';
            $indicatorClass = 'bg-slate-200';
            $badgeClass = 'bg-slate-100 text-slate-500';
            $dotClass = 'bg-slate-400';
            $iconBgClass = 'bg-slate-100 text-slate-500';

            if ($completed) {
                $borderClass = 'border-emerald-200 bg-emerald-50/30';
                $indicatorClass = 'bg-emerald-500';
                $badgeClass = 'bg-emerald-100 text-emerald-700';
                $dotClass = 'bg-emerald-500';
            } elseif ($running) {
                if ($isGrout) {
                    if (str_contains($statusLower, 'packing') && !str_contains($statusLower, 'ready')) {
                        $borderClass = 'border-purple-200 bg-purple-50/40';
                        $indicatorClass = 'bg-purple-500';
                        $badgeClass = 'bg-purple-100 text-purple-700';
                        $dotClass = 'bg-purple-500 animate-pulse';
                        $iconBgClass = 'bg-purple-500 text-white shadow-lg shadow-purple-500/20';
                    } elseif (str_contains($statusLower, 'ready')) {
                        $borderClass = 'border-teal-200 bg-teal-50/40';
                        $indicatorClass = 'bg-teal-500';
                        $badgeClass = 'bg-teal-100 text-teal-700';
                        $dotClass = 'bg-teal-500 animate-pulse';
                        $iconBgClass = 'bg-teal-500 text-white shadow-lg shadow-teal-500/20';
                    } else {
                        $borderClass = 'border-cyan-200 bg-cyan-50/40';
                        $indicatorClass = 'bg-cyan-500';
                        $badgeClass = 'bg-cyan-100 text-cyan-700';
                        $dotClass = 'bg-cyan-500 animate-pulse';
                        $iconBgClass = 'bg-cyan-500 text-white shadow-lg shadow-cyan-500/20';
                    }
                } else {
                    $borderClass = 'border-blue-200 bg-blue-50/50';
                    $indicatorClass = 'bg-blue-600';
                    $badgeClass = 'bg-blue-100 text-blue-700';
                    $dotClass = 'bg-blue-600 animate-pulse';
                    $iconBgClass = 'bg-blue-600 text-white shadow-lg shadow-blue-600/20';
                }
            }
        @endphp

        <article class="group relative overflow-hidden rounded-[20px] border {{ $borderClass }} p-4 transition duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-slate-900/5">
            <span class="sr-only">Mixer ID: {{ $machine['machine_id'] }}</span>
            <div class="absolute inset-y-0 left-0 w-1 {{ $indicatorClass }}"></div>
            
            <div class="flex items-start justify-between gap-3">
                <div class="flex min-w-0 items-center gap-3">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl {{ $iconBgClass }}">
                        <i data-lucide="cpu" class="h-5 w-5"></i>
                    </span>
                    <div class="min-w-0">
                        <p class="truncate text-sm font-extrabold text-slate-900">{{ $machine['machine_name'] }}</p>
                        <p class="mt-0.5 font-mono text-[10px] font-bold uppercase tracking-wider text-slate-400">
                            {{ $machine['machine_code'] }} · <span class="text-slate-500 font-semibold">{{ $isGrout ? 'Grout' : 'Adhesive' }}</span>
                        </p>
                    </div>
                </div>
                <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[9px] font-extrabold uppercase tracking-wider {{ $badgeClass }}">
                    <span class="h-1.5 w-1.5 rounded-full {{ $dotClass }}"></span>
                    {{ $machine['status'] }}
                </span>
            </div>

            @if($running || ($completed && isset($machine['batch_id'])))
                <div class="mt-4 grid grid-cols-2 gap-2 rounded-2xl border {{ $running ? 'border-slate-100 bg-white/80' : 'border-emerald-100 bg-white/80' }} p-3">
                    <div>
                        <p class="text-[9px] font-bold uppercase tracking-wider text-slate-400">{{ $isGrout ? 'Color' : 'Grade' }}</p>
                        <p class="mt-1 truncate text-xs font-extrabold text-slate-800">{{ $machine['grade'] }}</p>
                    </div>
                    <div>
                        @if($running)
                            <p class="text-[9px] font-bold uppercase tracking-wider text-slate-400">Elapsed</p>
                            <p class="live-timer mt-1 font-mono text-xs font-extrabold text-slate-700" data-start="{{ $machine['start_time'] }}">00:00:00</p>
                        @else
                            <p class="text-[9px] font-bold uppercase tracking-wider text-slate-400">Status</p>
                            <p class="mt-1 text-xs font-extrabold text-emerald-600">Finalized</p>
                        @endif
                    </div>
                    <div class="col-span-2 flex items-center justify-between border-t border-slate-100 pt-2">
                        <a href="{{ $activeRoute }}" class="font-mono text-[10px] font-bold text-slate-500 hover:text-cyan-600 hover:underline transition-colors">
                            {{ $machine['batch_no'] }}
                        </a>
                        <span class="truncate text-[10px] font-semibold text-slate-500">{{ $machine['supervisor'] }}</span>
                    </div>
                </div>
            @else
                <div class="mt-4 flex min-h-[76px] items-center justify-between gap-3 rounded-2xl border border-dashed border-slate-200 bg-slate-50/70 p-3">
                    <div>
                        <p class="text-xs font-bold text-slate-600">Ready for production</p>
                        <p class="mt-0.5 text-[10px] text-slate-400">No active batch on this mixer</p>
                    </div>
                    <a href="{{ $createRoute }}" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-white shadow-md shadow-blue-600/20 transition hover:scale-105" title="Start batch">
                        <i data-lucide="plus" class="h-5 w-5"></i>
                    </a>
                </div>
            @endif
        </article>
    @empty
        <div class="col-span-full flex flex-col items-center justify-center rounded-[22px] border-2 border-dashed border-slate-200 bg-slate-50 px-6 py-12 text-center">
            <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white text-slate-300 shadow-sm"><i data-lucide="cpu" class="h-7 w-7"></i></span>
            <h4 class="mt-4 text-sm font-extrabold text-slate-700">No active machines yet</h4>
            <p class="mt-1 max-w-sm text-xs leading-5 text-slate-400">Add a machine in factory setup to begin monitoring your production floor.</p>
        </div>
    @endforelse
</div>
