<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RepairListingFilterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Filtreleme herkese açık
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'province_id' => 'nullable|integer|exists:provinces,id',
            'district_id' => 'nullable|integer|exists:districts,id',
            'brand_id' => 'nullable|integer|exists:brands,id',
            'model_id' => 'nullable|integer|exists:models,id',
            'status' => 'nullable|in:draft,active,completed,inactive',

            // Pagination
            'per_page' => 'nullable|integer|min:1|max:100',
            'page' => 'nullable|integer|min:1',

            // Sorting
            'sort_by' => 'nullable|in:created_at,updated_at,published_at,total_offers_count,last_offer_at',
            'sort_direction' => 'nullable|in:asc,desc',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'province_id.exists' => 'Seçilen il bulunamadı.',
            'district_id.exists' => 'Seçilen ilçe bulunamadı.',
            'brand_id.exists' => 'Seçilen marka bulunamadı.',
            'model_id.exists' => 'Seçilen model bulunamadı.',
            'status.in' => 'Geçersiz durum değeri.',
        ];
    }

    /**
     * Get validated filters only (remove null values)
     */
    public function getFilters(): array
    {
        return array_filter($this->validated(), function ($value, $key) {
            // Pagination ve sorting parametrelerini filtrelerden ayır
            if (in_array($key, ['per_page', 'page', 'sort_by', 'sort_direction'])) {
                return false;
            }
            return !is_null($value);
        }, ARRAY_FILTER_USE_BOTH);
    }

    /**
     * Get pagination parameters
     */
    public function getPaginationParams(): array
    {
        return [
            'per_page' => $this->input('per_page', 15),
            'page' => $this->input('page', 1),
        ];
    }

    /**
     * Get sorting parameters
     */
    public function getSortingParams(): array
    {
        return [
            'sort_by' => $this->input('sort_by', 'created_at'),
            'sort_direction' => $this->input('sort_direction', 'desc'),
        ];
    }
}
