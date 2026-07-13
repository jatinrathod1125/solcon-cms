<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Name -->
    <div>
        <label for="name" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Full Name</label>
        <input type="text" id="name" name="name" value="{{ old('name', $user->name ?? '') }}" required autofocus
            class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm"
            placeholder="e.g. Rahul Sharma">
        @error('name')
            <p class="text-rose-455 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Email -->
    <div>
        <label for="email" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Email Address</label>
        <input type="email" id="email" name="email" value="{{ old('email', $user->email ?? '') }}" required
            class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm"
            placeholder="username@solcon.com">
        @error('email')
            <p class="text-rose-455 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

@if(isset($user))
    <div class="p-4 bg-slate-900/35 border border-slate-850 rounded-xl space-y-4">
        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Change Password (Optional)</h4>
        <p class="text-[11px] text-slate-500">Leave these fields blank if you do not want to change the user's password.</p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Password -->
            <div>
                <label for="password" class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">New Password</label>
                <input type="password" id="password" name="password"
                    class="block w-full px-3.5 py-2 bg-slate-900 border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm"
                    placeholder="••••••••">
                @error('password')
                    <p class="text-rose-455 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Confirmation -->
            <div>
                <label for="password_confirmation" class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Confirm New Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                    class="block w-full px-3.5 py-2 bg-slate-900 border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm"
                    placeholder="••••••••">
            </div>
        </div>
    </div>
@else
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Password</label>
            <input type="password" id="password" name="password" required
                class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm"
                placeholder="••••••••">
            @error('password')
                <p class="text-rose-455 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password Confirmation -->
        <div>
            <label for="password_confirmation" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required
                class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm"
                placeholder="••••••••">
        </div>
    </div>
@endif

<!-- Role Dropdown -->
<div>
    <label for="role_id" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">System Role</label>
    <select id="role_id" name="role_id" required
        class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm">
        @if(!isset($user))
            <option value="" disabled selected>Select user role...</option>
        @endif
        @foreach($roles as $role)
            <option value="{{ $role->id }}" {{ old('role_id', $userRole->id ?? '') == $role->id ? 'selected' : '' }}>
                {{ $role->name }} ({{ $role->description }})
            </option>
        @endforeach
    </select>
    @error('role_id')
        <p class="text-rose-455 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

<!-- Multiple Department Checkboxes -->
<div>
    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Assigned Departments</label>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 bg-slate-900/40 p-4 border border-slate-800 rounded-xl">
        @php
            $assignedIds = old('departments', isset($user) ? $user->departments->pluck('id')->toArray() : []);
        @endphp
        @foreach($departments as $dept)
            <label class="flex items-center text-sm text-slate-350 hover:text-white cursor-pointer select-none">
                <input type="checkbox" name="departments[]" value="{{ $dept->id }}" 
                    {{ in_array($dept->id, $assignedIds) ? 'checked' : '' }}
                    class="w-4 h-4 rounded border-slate-800 bg-slate-950 text-cyan-500 focus:ring-cyan-500/30 focus:ring-offset-0 mr-2.5 cursor-pointer">
                <span class="font-mono text-xs text-cyan-400 bg-slate-950 border border-slate-850 px-1.5 py-0.5 rounded-md mr-1.5 uppercase font-bold">{{ $dept->code }}</span>
                <span>{{ $dept->name }}</span>
            </label>
        @endforeach
    </div>
    @error('departments')
        <p class="text-rose-455 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

<!-- Active Status Toggle -->
<div class="flex items-center">
    <label class="flex items-center text-sm text-slate-400 cursor-pointer">
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active ?? true) ? 'checked' : '' }}
            class="w-4 h-4 rounded border-slate-800 bg-slate-900 text-cyan-500 focus:ring-cyan-500/30 focus:ring-offset-0 mr-2 cursor-pointer">
        <span>Mark User Account as Active (Allowed to log in)</span>
    </label>
    @error('is_active')
        <p class="text-rose-455 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>
