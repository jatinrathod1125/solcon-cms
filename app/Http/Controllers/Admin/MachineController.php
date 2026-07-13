<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Machine;
use App\Models\Department;
use App\Http\Requests\Admin\StoreMachineRequest;
use App\Http\Requests\Admin\UpdateMachineRequest;
use Illuminate\Http\Request;

class MachineController extends Controller
{
    /**
     * Display a listing of the machines.
     */
    public function index(Request $request)
    {
        $query = Machine::with('department');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->input('department_id'));
        }

        $machines = $query->latest()->paginate(10)->withQueryString();
        $departments = Department::getActive();

        return view('admin.machines.index', compact('machines', 'departments'));
    }

    /**
     * Show the form for creating a new machine.
     */
    public function create()
    {
        $departments = Department::getActive();
        return view('admin.machines.create', compact('departments'));
    }

    /**
     * Store a newly created machine in storage.
     */
    public function store(StoreMachineRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        Machine::create($data);

        return redirect()->route('admin.machines.index')
            ->with('success', 'Machine created successfully.');
    }

    /**
     * Show the form for editing the machine.
     */
    public function edit(Machine $machine)
    {
        $departments = Department::getActive();
        return view('admin.machines.edit', compact('machine', 'departments'));
    }

    /**
     * Update the machine in storage.
     */
    public function update(UpdateMachineRequest $request, Machine $machine)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        $machine->update($data);

        return redirect()->route('admin.machines.index')
            ->with('success', 'Machine updated successfully.');
    }

    /**
     * Remove the machine from storage.
     */
    public function destroy(Machine $machine)
    {
        // Note: When production module is built, check if batches exist for this machine.
        $machine->delete();

        return redirect()->route('admin.machines.index')
            ->with('success', 'Machine deleted successfully.');
    }
}
