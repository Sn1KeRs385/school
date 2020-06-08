<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Horizon\Horizon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \App\Media::observe(\App\Observers\MediaObserver::class);

        $morph_array = [
        ];

        if ($this->inAdminRoute(request())) {

            $morph_array = [
            ];
        }
        Relation::morphMap($morph_array);
    }

    protected $admin_routes = [
        '/api/v1/admin/*',
    ];

    /**
     * Determine if the request has a URI that should pass through CSRF verification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function inAdminRoute($request)
    {
        foreach ($this->admin_routes as $admin_route) {
            if ($admin_route !== '/') {
                $admin_route = trim($admin_route, '/');

            }

            if ($request->fullUrlIs($admin_route) || $request->is($admin_route)) {
                return true;
            }
        }

        return false;
    }
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
