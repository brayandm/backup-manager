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
            'timezone' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Invalid input',
            ], 400);
        }

        $timezone = $request->input('timezone');

        return $this->analyticsService->getOverview($timezone);
    }
}
