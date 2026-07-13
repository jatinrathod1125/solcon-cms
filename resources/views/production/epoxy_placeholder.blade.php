@extends('layouts.app')

@section('title', 'Epoxy Department')
@section('header-title', 'Epoxy Production OS')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 animate-fade-in">
    <!-- Back to Dashboard -->
    <div>
        <a href="{{ Auth::user()->isAdmin() ? route('admin.dashboard') : route('supervisor.dashboard') }}" 
           class="inline-flex items-center text-sm text-slate-400 hover:text-white transition-colors gap-2">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Back to Dashboard</span>
        </a>
    </div>

    <!-- Status Header Card -->
    <div class="bg-slate-950 border border-slate-850 rounded-[24px] p-6 md:p-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 shadow-2xl">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-purple-650/10 border border-purple-500/20 flex items-center justify-center text-purple-400">
                <i data-lucide="box" class="w-7 h-7"></i>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-xl md:text-2xl font-black text-white leading-tight">Epoxy Production OS</h2>
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-purple-500/10 text-purple-400 border border-purple-500/20 text-[10px] font-bold uppercase tracking-wider font-mono">
                        Engine Locked
                    </span>
                </div>
                <p class="text-sm text-slate-400 mt-1 leading-relaxed">
                    Preparing the production pipeline for resin-based formulas, packing, and automatic stock ledgers.
                </p>
            </div>
        </div>
        
        <div class="flex flex-col items-start md:items-end text-xs shrink-0">
            <span class="text-slate-500 block uppercase font-bold tracking-widest text-[9px]">Department Status</span>
            <span class="text-slate-350 mt-1 font-bold">Awaiting Formula Configuration</span>
        </div>
    </div>

    <!-- Proposed Workflow Timeline Card -->
    <div class="bg-slate-950 border border-slate-850 rounded-[24px] p-6 shadow-2xl space-y-6">
        <div class="flex items-center gap-3 border-b border-slate-850 pb-4">
            <i data-lucide="activity" class="w-5 h-5 text-purple-400"></i>
            <div>
                <h3 class="text-sm font-bold text-white uppercase tracking-wider">Dynamic Packing Workflow (Planned)</h3>
                <p class="text-xs text-slate-500 mt-0.5">High-fidelity automation stages for Epoxy mix runs.</p>
            </div>
        </div>

        <!-- Timeline steps -->
        <div class="relative pl-6 border-l border-slate-850 space-y-8">
            <!-- Step 1 -->
            <div class="relative">
                <span class="absolute -left-[31px] top-0.5 flex h-4 w-4 items-center justify-center rounded-full bg-purple-500 border-2 border-slate-950 shadow-lg shadow-purple-500/20"></span>
                <div>
                    <h4 class="text-sm font-bold text-white">Stage 1: Resin Loading &amp; Coloring</h4>
                    <p class="text-xs text-slate-400 mt-1 leading-normal">Load base Epoxy resins, pigment colors, filler extenders, and stabilizer chemicals. Auto-validate snapshot weight.</p>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="relative">
                <span class="absolute -left-[31px] top-0.5 flex h-4 w-4 items-center justify-center rounded-full bg-slate-900 border-2 border-slate-850"></span>
                <div>
                    <h4 class="text-sm font-bold text-slate-400">Stage 2: Hardener Binding (Second Stage Injection)</h4>
                    <p class="text-xs text-slate-500 mt-1 leading-normal">Trigger dynamic second stage hardener base loading. System determines specific hardener types based on temperature and formula configuration.</p>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="relative">
                <span class="absolute -left-[31px] top-0.5 flex h-4 w-4 items-center justify-center rounded-full bg-slate-900 border-2 border-slate-850"></span>
                <div>
                    <h4 class="text-sm font-bold text-slate-400">Stage 3: High-Shear Mixing Timer</h4>
                    <p class="text-xs text-slate-500 mt-1 leading-normal">Start the 45-minute continuous high-shear mixing process. Emergency overrides and validation controls will be active.</p>
                </div>
            </div>

            <!-- Step 4 -->
            <div class="relative">
                <span class="absolute -left-[31px] top-0.5 flex h-4 w-4 items-center justify-center rounded-full bg-slate-900 border-2 border-slate-850"></span>
                <div>
                    <h4 class="text-sm font-bold text-slate-400">Stage 4: Automated Twin Packing (1 KG &amp; 5 KG Buckets)</h4>
                    <p class="text-xs text-slate-500 mt-1 leading-normal">Pack finished epoxy paste into twin bucket configurations. Touch adjustments and virtual stock calculations are carried out.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Master setup notification -->
    <div class="bg-purple-950/20 border border-purple-900/50 rounded-2xl p-5 text-purple-300 text-xs flex gap-3">
        <i data-lucide="info" class="w-5 h-5 shrink-0 mt-0.5"></i>
        <div>
            <strong class="font-bold text-slate-100 uppercase tracking-wider block">EPOS MASTER INITIALIZATION:</strong>
            <p class="mt-1 leading-relaxed text-slate-350">
                To unlock this department workspace, please configure the Epoxy products catalog, formulas, and machines in the Factory Setup module first. The dynamic production engine is ready to capture these records.
            </p>
        </div>
    </div>
</div>
@endsection
