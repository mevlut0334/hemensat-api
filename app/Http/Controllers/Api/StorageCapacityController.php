<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StorageCapacity;
use Illuminate\Http\JsonResponse;

class StorageCapacityController extends Controller
{
    /**
     * Tüm aktif depolama kapasitelerini getir
     */
    public function index(): JsonResponse
    {
        $storageCapacities = StorageCapacity::where('status', true)
            ->orderBy('capacity')
            ->get(['id', 'capacity']);

        return response()->json([
            'data' => $storageCapacities
        ]);
    }
}
