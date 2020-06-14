<?php

namespace App\Providers;

use App\Helpers\ROLES;
use Illuminate\Support\Facades\Gate;
use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\Telescope;
use Laravel\Telescope\TelescopeApplicationServiceProvider;

class TelescopeServiceProvider extends TelescopeApplicationServiceProvider
{

    protected $dontReport = [
        '/',
        '/api/v1/admin/amount',
    ];


    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Telescope::night();

        $this->hideSensitiveRequestDetails();

        Telescope::filter(function (IncomingEntry $entry) {
           $entry = $entry->toArray();
           if($entry['type'] === 'request'
               && in_array($entry['content']['uri'], $this->dontReport, true)
           ) {
               return false;
           }
           return true;
        });

//        Telescope::filter(function (IncomingEntry $entry) {
//            if ($this->app->environment('local')) {
//                return true;
//            }
//
//            return $entry->isReportableException() ||
//                   $entry->isFailedRequest() ||
//                   $entry->isFailedJob() ||
//                   $entry->isScheduledTask() ||
//                   $entry->hasMonitoredTag();
//        });
    }

    /**
     * Prevent sensitive request details from being logged by Telescope.
     *
     * @return void
     */
    protected function hideSensitiveRequestDetails()
    {
        if ($this->app->environment('local')) {
            return;
        }

        Telescope::hideRequestParameters(['_token']);

        Telescope::hideRequestHeaders([
            'cookie',
            'x-csrf-token',
            'x-xsrf-token',
        ]);
    }

    /**
     * Register the Telescope gate.
     *
     * This gate determines who can access Telescope in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewTelescope', function ($user) {
            return $user->role_id === ROLES::IS_ADMIN;
        });
    }
}
