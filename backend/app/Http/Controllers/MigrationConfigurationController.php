<?php

namespace App\Http\Controllers;

use App\Models\MigrationConfiguration;
use App\Services\MigrationService;
use Illuminate\Http\Request;

class MigrationConfigurationController extends Controller
{
    private MigrationService $migrationService;

    public function __construct(MigrationService $migrationService)
    {
        $this->migrationService = $migrationService;
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

        return $this->migrationService->getMigrationConfigurations($pagination, $page, $sort_by, $sort_order, $filters);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string',
            'data_source_id' => 'required|integer',
            'data_source_ids' => 'required|array',
            'schedule_cron' => 'required|string',
            'manual_migration' => 'required|boolean',
            'compression_config' => 'required|json',
        ];

        $validatedData = $request->validate($rules);

        return $this->migrationService->storeMigrationConfiguration($validatedData);
    }

    public function show(Request $request, $id)
    {
        return $this->migrationService->getMigrationConfiguration($id);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required|string',
            'data_source_id' => 'required|integer',
            'data_source_ids' => 'required|array',
            'schedule_cron' => 'required|string',
            'manual_migration' => 'required|boolean',
            'compression_config' => 'required|json',
        ];

        $validatedData = $request->validate($rules);

        return $this->migrationService->updateMigrationConfiguration($id, $validatedData);
    }

    public function delete(Request $request, $id)
    {
        return $this->migrationService->deleteMigrationConfiguration($id);
    }

    public function deleteMultiple(Request $request)
    {
        return $this->migrationService->deleteMigrationConfigurations($request->input('ids'));
    }

    public function deleteAllExcept(Request $request)
    {
        return $this->migrationService->deleteAllMigrationConfigurationsExcept($request->input('ids'));
    }

    public function makeMigration(Request $request, $id)
    {
        $migrationConfiguration = MigrationConfiguration::findOrFail($id);

        return $this->migrationService->migrate($migrationConfiguration);
    }
}
