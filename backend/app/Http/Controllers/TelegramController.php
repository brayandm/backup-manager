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

    public function getSettings()
    {
        return $this->telegramService->getSettings();
    }

    public function updateSettings(Request $request)
    {
        $rules = [
            'telegram_bot_active' => 'required|string',
            'telegram_bot_api_key' => 'nullable|string',
            'telegram_channel_id' => 'nullable|string',
            'telegram_notify_backups' => 'required|string',
            'telegram_notify_migrations' => 'required|string',
        ];

        $validatedData = $request->validate($rules);

        $validatedData['telegram_bot_api_key'] = isset($validatedData['telegram_bot_api_key']) ? $validatedData['telegram_bot_api_key'] : '';
        $validatedData['telegram_channel_id'] = isset($validatedData['telegram_channel_id']) ? $validatedData['telegram_channel_id'] : '';

        $this->telegramService->updateSettings($validatedData);

        return response()->json(['message' => 'Settings updated successfully']);
    }
}
