<?php

namespace App\Providers;

use App\Events\ReportSubmitted;
use App\Listeners\AwardReportSubmitPoints;
use App\Models\Report;
use App\Policies\ReportPolicy;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Event & Listener — sistem poin via event (bukan hardcode di controller)
        Event::listen(ReportSubmitted::class, AwardReportSubmitPoints::class);

        // Policy
        Gate::policy(Report::class, ReportPolicy::class);
    }
}
