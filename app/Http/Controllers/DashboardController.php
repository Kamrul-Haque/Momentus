<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\Event;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        if (auth()->user()->hasRole(Role::SUPER_ADMIN->value))
            return inertia('Dashboard');

        $events = Event::with('users')
                       ->whereDate('start_at', today()->toDateString())
                       ->limit(15)
                       ->get();

        return inertia('UserDashboard', ['events' => $events]);
    }
}
