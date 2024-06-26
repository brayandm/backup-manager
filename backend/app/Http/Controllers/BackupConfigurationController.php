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
        $rules = [
            'pagination' => 'sometimes|integer|min:1',
            'page' => 'sometimes|integer|min:1',
            'sort_by' => 'sometimes|string',
            'sort_order' => 'sometimes|in:asc,desc',
            'filters' => 'sometimes|array',
        ];

        $validatedData = $request->validate($rules);

        $pagination = $validatedData['pagination'] ?? 10;
        $page = $validatedData['page'] ?? 1;
        $sort_by = $validatedData['sort_by'] ?? 'created_at';
        $sort_order = $validatedData['sort_order'] ?? 'desc';
        $filters = $validatedData['filters'] ?? [];

        return $this->backupService->getBackupConfigurations($pagination, $page, $sort_by, $sort_order, $filters);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string',
            'storage_server_ids' => 'required|array',
            'connection_config' => 'required|json',
            'driver_config' => 'required|json',
            'schedule_cron' => 'required|string',
            'retention_policy_config' => 'required|json',
            'compression_config' => 'required|json',
            'encryption_config' => 'required|json',
            'integrity_check_config' => 'required|json',
        ];

        $validatedData = $request->validate($rules);

        return $this->backupService->storeBackupConfiguration($validatedData);
    }

    public function show(Request $request, $id)
    {
        return null;
    }

    public function update(Request $request, $id)
    {
        return null;
    }

    public function delete(Request $request, $id)
    {
        return $this->backupService->deleteBackupConfiguration($id);
    }

    public function deleteMultiple(Request $request)
    {
        return $this->backupService->deleteBackupConfigurations($request->input('ids'));
    }

    public function deleteAllExcept(Request $request)
    {
        return $this->backupService->deleteAllBackupConfigurationsExcept($request->input('ids'));
    }
}
