<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Collection;
use App\Models\District;

interface DistrictServiceInterface
{
    /**
     * Get all active districts
     *
     * @return Collection
     */
    public function getAllActiveDistricts(): Collection;

    /**
     * Get districts by province ID
     *
     * @param int $provinceId
     * @return Collection
     */
    public function getDistrictsByProvinceId(int $provinceId): Collection;

    /**
     * Find district by ID
     *
     * @param int $id
     * @return District|null
     */
    public function findDistrictById(int $id): ?District;

    /**
     * Find district by ID with province
     *
     * @param int $id
     * @return District|null
     */
    public function findDistrictWithProvince(int $id): ?District;
}
