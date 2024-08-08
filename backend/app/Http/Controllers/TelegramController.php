<?php

namespace App\Http\Controllers;

use App\Services\TelegramService;
use Illuminate\Http\Request;

class TelegramController extends Controller
{
    private TelegramService $telegramService;

    public function __construct(TelegramService $telegramService)
    {
        $this->telegramService = $telegramService;
    }

    public function updateSettings(Request $request)
    {
        $rules = [
            'telegram_bot_active' => 'required|string',
            'telegram_bot_api_key' => 'sometimes|string',
            'telegram_channel_id' => 'sometimes|string',
        ];

        $validatedData = $request->validate($rules);

        $this->telegramService->updateSettings($validatedData);
    }
}
