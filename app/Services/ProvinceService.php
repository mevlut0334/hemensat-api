<?php

namespace App\Services;

use App\Contracts\ProvinceServiceInterface;
use App\Contracts\ProvinceRepositoryInterface;
use App\Models\Province;
use Illuminate\Database\Eloquent\Collection;

class ProvinceService implements ProvinceServiceInterface
{
    /**
     * @var ProvinceRepositoryInterface
     */
    protected $provinceRepository;

    /**
     * ProvinceService constructor.
     *
     * @param ProvinceRepositoryInterface $provinceRepository
     */
    public function __construct(ProvinceRepositoryInterface $provinceRepository)
    {
        $this->provinceRepository = $provinceRepository;
    }

    /**
     * Get all active provinces
     *
     * @return Collection
     */
    public function getAllActiveProvinces(): Collection
    {
        return $this->provinceRepository->getAllActive();
    }

    /**
     * Get provinces by region
     *
     * @param string $region
     * @return Collection
     */
    public function getProvincesByRegion(string $region): Collection
    {
        // Business logic: Region validation yapılabilir
        if (empty(trim($region))) {
            return collect([]);
        }

        return $this->provinceRepository->getByRegion($region);
    }

    /**
     * Find province by ID
     *
     * @param int $id
     * @return Province|null
     */
    public function findProvinceById(int $id): ?Province
    {
        // Business logic: ID validation
        if ($id <= 0) {
            return null;
        }

        return $this->provinceRepository->findById($id);
    }

    /**
     * Find province by ID with districts
     *
     * @param int $id
     * @return Province|null
     */
    public function findProvinceWithDistricts(int $id): ?Province
    {
        // Business logic: ID validation
        if ($id <= 0) {
            return null;
        }

        return $this->provinceRepository->findByIdWithDistricts($id);
    }

    /**
     * Find province by plate code
     *
     * @param string $plateCode
     * @return Province|null
     */
    public function findProvinceByPlateCode(string $plateCode): ?Province
    {
        // Business logic: Plate code validation
        if (empty(trim($plateCode))) {
            return null;
        }

        return $this->provinceRepository->findByPlateCode($plateCode);
    }
}
