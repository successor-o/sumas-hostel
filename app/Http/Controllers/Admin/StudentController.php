<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query()->with(['activeAllocation.hostel', 'activeAllocation.room']);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('matric_number', 'like', "%{$search}%");
            });
        }

        if ($faculty = $request->get('faculty')) {
            $query->where('faculty', $faculty);
        }

        if ($level = $request->get('level')) {
            $query->where('level', $level);
        }

        if ($gender = $request->get('gender')) {
            $query->where('gender', $gender);
        }

        if ($housing = $request->get('housing')) {
            if ($housing === 'housed') {
                $query->whereHas('activeAllocation');
            } elseif ($housing === 'not_housed') {
                $query->whereDoesntHave('activeAllocation');
            }
        }

        $students = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('admin.students', [
            'students' => $students,
            'totalStudents' => User::count(),
            'housedCount' => User::whereHas('activeAllocation')->count(),
            'pendingCount' => \App\Models\HostelApplication::pending()->count(),
            'newThisMonth' => User::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
            'faculties' => User::select('faculty')->distinct()->whereNotNull('faculty')->pluck('faculty'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'matric_number' => ['required', 'string', 'max:50', 'unique:users,matric_number'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'faculty' => ['required', 'string', 'max:255'],
            'level' => ['required', 'string', 'max:50'],
            'gender' => ['required', 'in:Male,Female'],
        ]);

        User::create(array_merge($data, ['password' => Hash::make('password')]));

        return back()->with('status', 'New student added successfully.');
    }

    public function update(Request $request, User $student): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'matric_number' => ['required', 'string', 'max:50', 'unique:users,matric_number,'.$student->id],
            'faculty' => ['required', 'string', 'max:255'],
            'level' => ['required', 'string', 'max:50'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$student->id],
        ]);

        $student->update($data);

        return back()->with('status', 'Student profile updated.');
    }

    public function destroy(User $student): RedirectResponse
    {
        $student->delete();

        return back()->with('status', 'Student record deleted.');
    }
}
