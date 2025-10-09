<?php

namespace App\Services;

use App\Contracts\DistrictServiceInterface;
use App\Contracts\DistrictRepositoryInterface;
use App\Models\District;
use Illuminate\Database\Eloquent\Collection;

class DistrictService implements DistrictServiceInterface
{
    /**
     * @var DistrictRepositoryInterface
     */
    protected $districtRepository;

    /**
     * DistrictService constructor.
     *
     * @param DistrictRepositoryInterface $districtRepository
     */
    public function __construct(DistrictRepositoryInterface $districtRepository)
    {
        $this->districtRepository = $districtRepository;
    }

    /**
     * Get all active districts
     *
     * @return Collection
     */
    public function getAllActiveDistricts(): Collection
    {
        return $this->districtRepository->getAllActive();
    }

    /**
     * Get districts by province ID
     *
     * @param int $provinceId
     * @return Collection
     */
    public function getDistrictsByProvinceId(int $provinceId): Collection
    {
        // Business logic: Province ID validation
        if ($provinceId <= 0) {
            return collect([]);
        }

        // Kullanıcı kayıt formu için önemli: Sadece aktif ilçeleri getir
        return $this->districtRepository->getActiveByProvinceId($provinceId);
    }

    /**
     * Find district by ID
     *
     * @param int $id
     * @return District|null
     */
    public function findDistrictById(int $id): ?District
    {
        // Business logic: ID validation
        if ($id <= 0) {
            return null;
        }

        return $this->districtRepository->findById($id);
    }

    /**
     * Find district by ID with province
     *
     * @param int $id
     * @return District|null
     */
    public function findDistrictWithProvince(int $id): ?District
    {
        // Business logic: ID validation
        if ($id <= 0) {
            return null;
        }

        return $this->districtRepository->findByIdWithProvince($id);
    }
}
