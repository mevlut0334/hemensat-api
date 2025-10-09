<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Collection;
use App\Models\District;

interface DistrictRepositoryInterface
{
    /**
     * Get all active districts
     *
     * @return Collection
     */
    public function getAllActive(): Collection;

    /**
     * Get districts by province ID
     *
     * @param int $provinceId
     * @return Collection
     */
    public function getByProvinceId(int $provinceId): Collection;

    /**
     * Get active districts by province ID
     *
     * @param int $provinceId
     * @return Collection
     */
    public function getActiveByProvinceId(int $provinceId): Collection;

    /**
     * Find district by ID
     *
     * @param int $id
     * @return District|null
     */
    public function findById(int $id): ?District;

    /**
     * Find district by ID with province
     *
     * @param int $id
     * @return District|null
     */
    public function findByIdWithProvince(int $id): ?District;
}
