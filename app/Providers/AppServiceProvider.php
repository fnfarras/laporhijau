<?php

namespace App\Providers;

use App\Events\ReportSubmitted;
use App\Events\ReportVerified;
use App\Events\ReportResolved;
use App\Listeners\AwardReportSubmitPoints;
use App\Listeners\AwardVerificationPoints;
use App\Listeners\AwardResolvedPoints;
use App\Listeners\CheckAndAwardBadges;
use App\Models\Report;
use App\Models\Event;
use App\Policies\ReportPolicy;
use App\Policies\EventPolicy;
use Illuminate\Support\Facades\Event as EventFacade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // ── Events & Listeners: sistem poin (bukan hardcode di controller) ──
        EventFacade::listen(ReportSubmitted::class, AwardReportSubmitPoints::class);
        EventFacade::listen(ReportVerified::class,  AwardVerificationPoints::class);
        EventFacade::listen(ReportResolved::class,  AwardResolvedPoints::class);

        // ── Badge check setelah setiap event poin ───────────────────────────
        EventFacade::listen(ReportSubmitted::class, CheckAndAwardBadges::class);
        EventFacade::listen(ReportVerified::class,  CheckAndAwardBadges::class);
        EventFacade::listen(ReportResolved::class,  CheckAndAwardBadges::class);

        // ── Policies ────────────────────────────────────────────────────────
        Gate::policy(Report::class, ReportPolicy::class);
        Gate::policy(Event::class,  EventPolicy::class);
    }
}
