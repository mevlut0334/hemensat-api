<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PurchaseSource;
use Illuminate\Http\JsonResponse;

class PurchaseSourceController extends Controller
{
    /**
     * Tüm aktif satın alma kaynaklarını getir
     */
    public function index(): JsonResponse
    {
         $purchaseSources = PurchaseSource::where('status', true) // is_active yerine status
            ->orderBy('name')
            ->get(['id', 'name']); // description kaldırıldı

        return response()->json([
            'data' => $purchaseSources
        ]);
    }
}
