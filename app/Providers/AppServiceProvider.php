<?php

namespace App\Providers;

use App\Events\ReportSubmitted;
use App\Events\ReportVerified;
use App\Events\ReportResolved;
use App\Listeners\AwardReportSubmitPoints;
use App\Listeners\AwardVerificationPoints;
use App\Listeners\AwardResolvedPoints;
use App\Models\Report;
use App\Policies\ReportPolicy;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

use App\Listeners\CheckAndAwardBadges;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Event & Listener — sistem poin via event (bukan hardcode di controller)
        Event::listen(ReportSubmitted::class, AwardReportSubmitPoints::class);
        Event::listen(ReportVerified::class,  AwardVerificationPoints::class);
        Event::listen(ReportResolved::class,  AwardResolvedPoints::class);

        // Badge check — jalankan setelah setiap event poin
        Event::listen(ReportSubmitted::class, CheckAndAwardBadges::class);
        Event::listen(ReportVerified::class,  CheckAndAwardBadges::class);
        Event::listen(ReportResolved::class,  CheckAndAwardBadges::class);

        // Policy
        Gate::policy(Report::class, ReportPolicy::class);
    }
}
