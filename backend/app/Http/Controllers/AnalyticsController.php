<?php

namespace App\Http\Controllers;

use App\Services\AnalyticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AnalyticsController extends Controller
{
    private AnalyticsService $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    public function getOverview(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'currentUTCDate' => ['nullable', 'regex:/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}\.\d{3}Z$/'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Invalid date format. Please provide a valid UTC date in ISO 8601 format.',
            ], 400);
        }

        $currentUTCDate = $request->input('currentUTCDate');

        return $this->analyticsService->getOverview($currentUTCDate);
    }
}
