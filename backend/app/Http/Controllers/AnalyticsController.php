<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AnalyticsService;

class AnalyticsController extends Controller
{
    private AnalyticsService $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    public function getOverview(Request $request)
    {
        return $this->analyticsService->getOverview();
    }

}
