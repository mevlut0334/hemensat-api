<?php

namespace App\Http\Controllers\Api;

use App\Contracts\DistrictServiceInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class DistrictController extends Controller
{
    protected DistrictServiceInterface $districtService;

    public function __construct(DistrictServiceInterface $districtService)
    {
        $this->districtService = $districtService;
    }

    /**
     * Get districts by province ID.
     */
    public function show(int $provinceId): JsonResponse
    {
        $districts = $this->districtService->getDistrictsByProvinceId($provinceId);

        return response()->json($districts);
    }
}
