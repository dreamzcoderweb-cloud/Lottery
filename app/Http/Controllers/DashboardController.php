<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\User;
use App\Models\Booking;
use App\Models\Slot;
use Spatie\Permission\Models\Role;


class DashboardController extends Controller
{
    public function index()
    {
        $data['totalcustomers'] = Customer::count();
        $data['totalslots'] = Slot::whereNull('deleted_at')
    ->where('status', 'Active')
    ->count();
        $data['totalstaff'] = User::whereDoesntHave('roles', function ($query) {
            $query->whereIn('name', [
                'Super Admin',
                'Super-Admin',
                'super admin',
                'super-admin',
                'Admin',
                'admin',
            ]);
        })->count();
        $data['totalroles'] = Role::count();
        $data['todaywinnings_slot'] = Booking::where('is_winner', true)->whereDate('created_at', today())->count();
        //dd($data['todaywinnings_slot']);
        return view('dashboard',$data);
    }
}
