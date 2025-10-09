<?php

namespace App\Repositories;

use App\Models\District;
use App\Contracts\DistrictRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class DistrictRepository implements DistrictRepositoryInterface
{
    /**
     * @var District
     */
    protected $model;

    /**
     * DistrictRepository constructor.
     *
     * @param District $model
     */
    public function __construct(District $model)
    {
        $this->model = $model;
    }

    /**
     * Get all active districts
     *
     * @return Collection
     */
    public function getAllActive(): Collection
    {
        return $this->model->active()
                          ->with('province:id,name')
                          ->select('id', 'name', 'province_id')
                          ->orderBy('name')
                          ->get();
    }

    /**
     * Get districts by province ID
     *
     * @param int $provinceId
     * @return Collection
     */
    public function getByProvinceId(int $provinceId): Collection
    {
        return $this->model->byProvince($provinceId)
                          ->select('id', 'name', 'province_id')
                          ->orderBy('name')
                          ->get();
    }

    /**
     * Get active districts by province ID
     *
     * @param int $provinceId
     * @return Collection
     */
    public function getActiveByProvinceId(int $provinceId): Collection
    {
        return $this->model->active()
                          ->byProvince($provinceId)
                          ->select('id', 'name', 'province_id')
                          ->orderBy('name')
                          ->get();
    }

    /**
     * Find district by ID
     *
     * @param int $id
     * @return District|null
     */
    public function findById(int $id): ?District
    {
        return $this->model->active()
                          ->select('id', 'name', 'province_id')
                          ->find($id);
    }

    /**
     * Find district by ID with province
     *
     * @param int $id
     * @return District|null
     */
    public function findByIdWithProvince(int $id): ?District
    {
        return $this->model->active()
                          ->withProvince()
                          ->find($id);
    }
}
