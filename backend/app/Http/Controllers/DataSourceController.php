<?php

namespace App\Http\Controllers;

use App\Services\DataSourceService;
use Illuminate\Http\Request;

class DataSourceController extends Controller
{
    private DataSourceService $dataSourceService;

    public function __construct(DataSourceService $dataSourceService)
    {
        $this->dataSourceService = $dataSourceService;
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

        return $this->dataSourceService->getDataSources($pagination, $page, $sort_by, $sort_order, $filters);
    }

    public function getNames(Request $request)
    {
        return $this->dataSourceService->getDataSourceNames();
    }

    public function getMigrationCompatible(Request $request, $id)
    {
        return $this->dataSourceService->getMigrationCompatible($id);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string',
            'connection_config' => 'required|json',
            'driver_config' => 'required|json',
        ];

        $validatedData = $request->validate($rules);

        return $this->dataSourceService->storeDataSource($validatedData);
    }

    public function show(Request $request, $id)
    {
        return $this->dataSourceService->getDataSource($id);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required|string',
            'connection_config' => 'required|json',
            'driver_config' => 'required|json',
        ];

        $validatedData = $request->validate($rules);

        return $this->dataSourceService->updateDataSource($id, $validatedData);
    }

    public function delete(Request $request, $id)
    {
        return $this->dataSourceService->deleteDataSource($id);
    }

    public function deleteMultiple(Request $request)
    {
        $rules = [
            'ids' => 'array',
        ];

        $validatedData = $request->validate($rules);

        $ids = $validatedData['ids'];

        return $this->dataSourceService->deleteDataSources($ids);
    }

    public function deleteAllExcept(Request $request)
    {
        $rules = [
            'ids' => 'array',
        ];

        $validatedData = $request->validate($rules);

        $ids = $validatedData['ids'];

        return $this->dataSourceService->deleteAllDataSourcesExcept($ids);
    }

    public function download(Request $request, $id)
    {
        return $this->dataSourceService->downloadDataSource($id);
    }
}
