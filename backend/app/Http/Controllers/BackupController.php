<?php

namespace App\Http\Controllers;

use App\Services\BackupService;
use Illuminate\Http\Request;

class BackupController extends Controller
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

        return $this->backupService->getBackups($pagination, $page, $sort_by, $sort_order, $filters);
    }

    public function delete(Request $request, $id)
    {
        return $this->backupService->deleteBackup($id);
    }

    public function deleteMultiple(Request $request)
    {
        $rules = [
            'ids' => 'array',
        ];

        $validatedData = $request->validate($rules);

        $ids = $validatedData['ids'];

        return $this->backupService->deleteBackups($ids);
    }

    public function deleteAllExcept(Request $request)
    {
        $rules = [
            'backup_configuration_id' => 'required|integer',
            'ids' => 'array',
        ];

        $validatedData = $request->validate($rules);

        $backupConfigurationId = $validatedData['backup_configuration_id'];
        $ids = $validatedData['ids'];

        return $this->backupService->deleteAllBackupsExcept($ids, $backupConfigurationId);
    }

    public function restore(Request $request, $id)
    {
        $result = $this->backupService->restoreBackup($id);

        if ($result) {
            return response()->json(['message' => 'Backup restored successfully'], 200);
        } else {
            return response()->json(['message' => 'Failed to restore backup'], 500);
        }
    }

    public function download(Request $request, $id)
    {
        return $this->backupService->downloadBackup($id);
    }
}
