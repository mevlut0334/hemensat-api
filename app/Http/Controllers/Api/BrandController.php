<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\JsonResponse;

class BrandController extends Controller
{
    /**
     * Tüm aktif markaları getir
     */
    public function index(): JsonResponse
    {
        $brands = Brand::active()
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json([
            'data' => $brands
        ]);
    }

    /**
     * Markaya ait aktif modelleri getir
     */
    public function models(int $brandId): JsonResponse
    {
        $brand = Brand::active()->find($brandId);

        if (!$brand) {
            return response()->json([
                'message' => 'Marka bulunamadı.'
            ], 404);
        }

        $models = $brand->activeModels()
            ->orderBy('name')
            ->get(['id', 'name', 'brand_id']);

        return response()->json([
            'data' => $models
        ]);
    }
}
