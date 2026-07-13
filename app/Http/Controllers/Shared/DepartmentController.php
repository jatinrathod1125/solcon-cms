<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;

class DepartmentController extends Controller
{
    /**
     * Switch the current active department in user session.
     */
    public function switch(Request $request)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
        ]);

        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login');
        }

        $deptId = $request->input('department_id');

        if (!$user->canAccessDepartment($deptId)) {
            abort(403, 'You do not have permission to access this department.');
        }

        // Set active department ID in session for this user
        session(['current_department_id_' . $user->id => $deptId]);

        $dept = Department::find($deptId);
        $code = strtoupper($dept->code);
        if ($code === 'GRT') {
            return redirect()->route('grout-production.index')->with('success', 'Switched to Grout Department.');
        } elseif ($code === 'EPX' || $code === 'EP') {
            return redirect()->route('epoxy.index')->with('success', 'Switched to Epoxy Department.');
        } else {
            return redirect()->route('production.index')->with('success', 'Switched to Adhesive Department.');
        }
    }
}
