<?php

namespace App\Repositories;

use App\Models\Province;
use App\Contracts\ProvinceRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ProvinceRepository implements ProvinceRepositoryInterface
{
    /**
     * @var Province
     */
    protected $model;

    /**
     * ProvinceRepository constructor.
     *
     * @param Province $model
     */
    public function __construct(Province $model)
    {
        $this->model = $model;
    }

    /**
     * Get all active provinces
     *
     * @return Collection
     */
    public function getAllActive(): Collection
    {
        return $this->model->active()
                          ->select('id', 'name', 'code', 'plate_code', 'region')
                          ->orderBy('name')
                          ->get();
    }

    /**
     * Get provinces by region
     *
     * @param string $region
     * @return Collection
     */
    public function getByRegion(string $region): Collection
    {
        return $this->model->active()
                          ->byRegion($region)
                          ->select('id', 'name', 'code', 'plate_code', 'region')
                          ->orderBy('name')
                          ->get();
    }

    /**
     * Find province by ID
     *
     * @param int $id
     * @return Province|null
     */
    public function findById(int $id): ?Province
    {
        return $this->model->active()
                          ->select('id', 'name', 'code', 'plate_code', 'region')
                          ->find($id);
    }

    /**
     * Find province by ID with districts
     *
     * @param int $id
     * @return Province|null
     */
    public function findByIdWithDistricts(int $id): ?Province
    {
        return $this->model->active()
                          ->with(['districts' => function($query) {
                              $query->active()
                                   ->select('id', 'name', 'province_id')
                                   ->orderBy('name');
                          }])
                          ->find($id);
    }

    /**
     * Find province by plate code
     *
     * @param string $plateCode
     * @return Province|null
     */
    public function findByPlateCode(string $plateCode): ?Province
    {
        return $this->model->active()
                          ->byPlateCode($plateCode)
                          ->select('id', 'name', 'code', 'plate_code', 'region')
                          ->first();
    }
}
