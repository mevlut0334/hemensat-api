<?php

namespace App\Http\Controllers\Api;

use App\Contracts\ProvinceServiceInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ProvinceController extends Controller
{
    protected ProvinceServiceInterface $provinceService;

    public function __construct(ProvinceServiceInterface $provinceService)
    {
        $this->provinceService = $provinceService;
    }

    /**
     * Get all active provinces.
     */
    public function index(): JsonResponse
    {
        $provinces = $this->provinceService->getAllActiveProvinces();

        return response()->json($provinces);
    }
}
