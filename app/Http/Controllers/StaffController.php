<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class StaffController extends Controller
{
    public function index()
    {
        $data['staffs'] = User::with('roles')
            ->whereDoesntHave('roles', function ($q) {
                $q->whereIn('name', ['Super Admin', 'super admin', 'super-admin', 'Super-Admin']);
            })
            ->orderBy('id', 'DESC')
            ->get();

        return view('staff.view', $data);
    }

    public function add(Request $request)
    {
        if (!$_POST) {
            $data['roles'] = Role::orderBy('name')->get();
            return view('staff.add', $data);
        }

        $validated = $request->validate(
            [
                'name' => ['required', 'string', 'min:3', 'max:50'],
                'email' => ['required', 'email', 'max:255', 'unique:users,email'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'role_id' => ['required', 'integer', 'exists:roles,id'],
            ]
        );

        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);
        $user->save();

        $role = Role::find($validated['role_id']);
        if ($role) {
            $user->syncRoles([$role]);
        }

        session()->flash('success', 'Staff created successfully');
        return redirect('admin/staff');
    }

    public function update(Request $request, $id)
    {
        $user = User::with('roles')->find($id);
        if (!$user) {
            return view('errors.404');
        }

        if ($user->hasRole('Super Admin')) {
            session()->flash('danger', 'Super Admin user cannot be edited from Staff module');
            return redirect('admin/staff');
        }

        if (!$_POST) {
            $data['staff'] = $user;
            $data['roles'] = Role::orderBy('name')->get();
            $data['selectedRoleId'] = $user->roles->first()?->id;
            return view('staff.edit', $data);
        }

        $validated = $request->validate(
            [
                'name' => ['required', 'string', 'min:3', 'max:50'],
                'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
                'password' => ['nullable', 'string', 'min:8', 'confirmed'],
                'role_id' => ['required', 'integer', 'exists:roles,id'],
            ]
        );

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        $role = Role::find($validated['role_id']);
        if ($role) {
            $user->syncRoles([$role]);
        }

        session()->flash('success', 'Staff updated successfully');
        return redirect('admin/staff');
    }

    public function delete(Request $request, $id)
    {
        $user = User::with('roles')->find($id);
        if (!$user) {
            return view('errors.404');
        }

        if ($user->hasRole('Super Admin')) {
            session()->flash('danger', 'Super Admin user cannot be deleted');
            return redirect('admin/staff');
        }

        $user->delete();
        session()->flash('danger', 'Staff deleted successfully');
        return redirect('admin/staff');
    }
}

