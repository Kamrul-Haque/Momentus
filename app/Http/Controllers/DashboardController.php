<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        if (!auth()->user()->hasRole(Role::SUPER_ADMIN->value))
            return inertia('UserDashboard');

        return inertia('Dashboard');
    }
}
