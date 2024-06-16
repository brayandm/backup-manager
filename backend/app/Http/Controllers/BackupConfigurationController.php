<?php

namespace App\Http\Controllers;

use App\Services\BackupService;
use Illuminate\Http\Request;

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
