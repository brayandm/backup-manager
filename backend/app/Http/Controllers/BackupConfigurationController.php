<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BackupService;

class BackupConfigurationController extends Controller
{
    private BackupService $backupService;

    public function __construct(BackupService $backupService)
    {
        $this->backupService = $backupService;
    }

    public function index(Request $request)
    {
        return $this->backupService->getBackupConfigurations();
    }
}
