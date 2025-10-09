<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Province;

interface ProvinceRepositoryInterface
{
    /**
     * Get all active provinces
     *
     * @return Collection
     */
    public function getAllActive(): Collection;

    /**
     * Get provinces by region
     *
     * @param string $region
     * @return Collection
     */
    public function getByRegion(string $region): Collection;

    /**
     * Find province by ID
     *
     * @param int $id
     * @return Province|null
     */
    public function findById(int $id): ?Province;

    /**
     * Find province by ID with districts
     *
     * @param int $id
     * @return Province|null
     */
    public function findByIdWithDistricts(int $id): ?Province;

    /**
     * Find province by plate code
     *
     * @param string $plateCode
     * @return Province|null
     */
    public function findByPlateCode(string $plateCode): ?Province;
}
