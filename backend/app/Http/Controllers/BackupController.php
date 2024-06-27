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
        return $this->backupService->deleteBackups($request->input('ids'));
    }

    public function deleteAllExcept(Request $request)
    {
        return $this->backupService->deleteAllBackupsExcept($request->input('ids'));
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
}
