<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Province;

interface ProvinceServiceInterface
{
    /**
     * Get all active provinces
     *
     * @return Collection
     */
    public function getAllActiveProvinces(): Collection;

    /**
     * Get provinces by region
     *
     * @param string $region
     * @return Collection
     */
    public function getProvincesByRegion(string $region): Collection;

    /**
     * Find province by ID
     *
     * @param int $id
     * @return Province|null
     */
    public function findProvinceById(int $id): ?Province;

    /**
     * Find province by ID with districts
     *
     * @param int $id
     * @return Province|null
     */
    public function findProvinceWithDistricts(int $id): ?Province;

    /**
     * Find province by plate code
     *
     * @param string $plateCode
     * @return Province|null
     */
    public function findProvinceByPlateCode(string $plateCode): ?Province;
}
