@extends('layouts.app')

@section('title', 'Database Backups')
@section('header-title', 'Database Admin')

@section('content')
<div class="space-y-6">
    <!-- Top Header Banner -->
    <div class="bg-white border border-slate-200 p-6 rounded-2xl shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900 flex items-center gap-2">
                <i data-lucide="database" class="w-5 h-5 text-indigo-650"></i>
                <span>Database Backups Manager</span>
            </h1>
            <p class="text-sm text-slate-500 mt-0.5">Generate, download, and delete sql dumps of current factory databases configurations.</p>
        </div>

        <div class="flex items-center">
            <!-- Generate Backup Form Trigger -->
            <form action="{{ route('admin.backups.generate') }}" method="POST" id="backupGenForm">
                @csrf
                <button type="submit" id="genBtn" class="inline-flex items-center px-4 py-2 bg-indigo-650 hover:bg-indigo-750 text-white font-bold rounded-xl text-sm transition-all shadow-md shadow-indigo-600/10 gap-1.5">
                    <i data-lucide="plus-circle" class="w-4 h-4"></i>
                    <span>Generate Backup</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Backups Table Card -->
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-200 bg-slate-50/50">
            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                <i data-lucide="archive" class="w-4 h-4 text-slate-500"></i>
                <span>Saved Dumps History</span>
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/80 text-slate-550 font-semibold text-xs uppercase tracking-wider">
                        <th class="p-4">Backup File Name</th>
                        <th class="p-4">Created Date</th>
                        <th class="p-4 text-right">File Size</th>
                        <th class="p-4 w-48 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($backups as $bkp)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="p-4 font-mono font-semibold text-slate-800 flex items-center gap-2">
                                <i data-lucide="file-spreadsheet" class="w-4 h-4 text-slate-400"></i>
                                <span>{{ $bkp['filename'] }}</span>
                            </td>
                            <td class="p-4 text-slate-500 font-mono text-xs">
                                {{ $bkp['date'] }}
                            </td>
                            <td class="p-4 text-right font-mono font-bold text-slate-650">
                                {{ $bkp['size'] }}
                            </td>
                            <td class="p-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <!-- Download Backup -->
                                    <a href="{{ route('admin.backups.download', $bkp['filename']) }}" 
                                       class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 border border-slate-300 rounded-lg text-xs font-bold text-slate-750 transition-colors flex items-center gap-1"
                                       title="Download Dump">
                                        <i data-lucide="download" class="w-3.5 h-3.5"></i>
                                        <span>Download</span>
                                    </a>

                                    <!-- Restore Backup (UI Only) -->
                                    <button type="button" 
                                            class="px-2.5 py-1.5 bg-amber-500/10 hover:bg-amber-500/20 border border-amber-500/20 rounded-lg text-xs font-bold text-amber-700 transition-colors flex items-center gap-1 restore-trigger"
                                            data-file="{{ $bkp['filename'] }}"
                                            title="Restore DB State">
                                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                                        <span>Restore</span>
                                    </button>

                                    <!-- Delete Backup -->
                                    <form action="{{ route('admin.backups.destroy', $bkp['filename']) }}" method="POST" class="delete-form inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="px-2.5 py-1.5 bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/20 rounded-lg text-xs font-bold text-rose-700 transition-colors flex items-center gap-1 delete-trigger"
                                                title="Delete Backup File">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <i data-lucide="database-backup" class="w-8 h-8 text-slate-300"></i>
                                    <span>No backup records found. Click "Generate Backup" to create one.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Form submit loading dialog
        $('#backupGenForm').on('submit', function() {
            let btn = $('#genBtn');
            btn.prop('disabled', true).addClass('opacity-70');
            btn.find('span').text('Creating Database Backup...');
            
            Swal.fire({
                title: 'Compiling Database SQL Backup...',
                html: 'Aggregating schemas, locking database indexes, and writing dump files...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        });

        // Restore trigger SweetAlert
        $('.restore-trigger').on('click', function(e) {
            e.preventDefault();
            let file = $(this).data('file');

            Swal.fire({
                title: 'Confirm Database Restore?',
                text: "Warning: Attempting to restore database to state in: " + file + " is not fully enabled in this deployment.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Restore (UI Check)',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#f59e0b'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Restore Action Prohibited',
                        text: 'For safety, database restoring is disabled in the administration web demo workspace to prevent accidental production data overrides.',
                        icon: 'info',
                        confirmButtonColor: 'var(--primary-color, #4f46e5)'
                    });
                }
            });
        });

        // Delete trigger SweetAlert
        $('.delete-trigger').on('click', function(e) {
            e.preventDefault();
            let form = $(this).closest('form');

            Swal.fire({
                title: 'Delete Backup File?',
                text: "Warning: You are deleting this sql dump file from physical storage. This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#ef4444'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection
