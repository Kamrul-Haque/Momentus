<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $settings = Setting::first();

        return [
            ...parent::share($request),
            'app' => [
                'name' => config('app.name'),
                'url' => config('app.url'),
                'environment' => config('app.env'),
                'timezone' => config('app.timezone'),
                'today' => today()->toDateString(),
                'previous_url' => url()->previous(),
            ],
            'settings' => [
                'id' => $settings->id,
                'name' => $settings->name,
                'logo' => $settings->logo,
                'portal_logo' => $settings->portal_logo,
                'copyright_text' => $settings->copyright_text,
                'primaryColor' => $settings->primary_color,
                'secondaryColor' => $settings->secondary_color,
                'accentColor' => $settings->accent_color,
                'backgroundColor' => $settings->background_color,
                'tableStripeColor' => $settings->table_stripe_color,
            ],
            'auth' => [
                'user' => $request->user(),
                'roles' => Role::array(),
            ],
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(),
                ]);
            },
            'flash' => function () use ($request) {
                return [
                    'success' => $request->session()->get('success'),
                    'error' => $request->session()->get('error'),
                ];
            },
        ];
    }
}
