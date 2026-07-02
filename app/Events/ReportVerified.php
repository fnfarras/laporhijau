<?php

namespace App\Events;

use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReportVerified
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly Report $report,
        public readonly User   $relawan
    ) {}
}
