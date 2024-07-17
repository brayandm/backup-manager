<?php

namespace App\Http\Controllers;

use App\Services\StorageServerService;
use Illuminate\Http\Request;

class StorageServerController extends Controller
{
    private StorageServerService $storageServerService;

    public function __construct(StorageServerService $storageServerService)
    {
        $this->storageServerService = $storageServerService;
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

        return $this->storageServerService->getStorageServers($pagination, $page, $sort_by, $sort_order, $filters);
    }

    public function getNames(Request $request)
    {
        return $this->storageServerService->getStorageServerNames();
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string',
            'connection_config' => 'required|json',
            'driver_config' => 'required|json',
        ];

        $validatedData = $request->validate($rules);

        return $this->storageServerService->storeStorageServer($validatedData);
    }

    public function show(Request $request, $id)
    {
        return $this->storageServerService->getStorageServer($id);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required|string',
            'connection_config' => 'required|json',
            'driver_config' => 'required|json',
        ];

        $validatedData = $request->validate($rules);

        return $this->storageServerService->updateStorageServer($id, $validatedData);
    }

    public function delete(Request $request, $id)
    {
        return $this->storageServerService->deleteStorageServer($id);
    }

    public function deleteMultiple(Request $request)
    {
        $rules = [
            'ids' => 'array',
        ];

        $validatedData = $request->validate($rules);

        $ids = $validatedData['ids'];

        return $this->storageServerService->deleteStorageServers($ids);
    }

    public function deleteAllExcept(Request $request)
    {
        $rules = [
            'ids' => 'array',
        ];

        $validatedData = $request->validate($rules);

        $ids = $validatedData['ids'];

        return $this->storageServerService->deleteAllStorageServersExcept($ids);
    }
}
