<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreRepairListingRequest extends FormRequest
{
    /**
     * Kullanıcının bu isteği yapmaya yetkili olup olmadığını belirler.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * İstek için geçerli doğrulama kurallarını alır.
     */
    public function rules(): array
    {
        return [
            // Temel ilan bilgileri
            'title' => ['required', 'string', 'max:150'],
            'description' => ['required', 'string', 'max:5000'],
            'is_urgent' => ['nullable', 'boolean'],
            'phone' => 'required|string|max:20|regex:/^[\+]?[0-9\s\-\(\)]+$/',

            // Cihaz ve konum bilgileri
            'brand_id' => ['required', 'integer', 'exists:brands,id'],
            "model_id" => ['required', 'integer', 'exists:models,id'],

            // Opsiyonel alanlar
            'storage_capacity_id' => ['nullable', 'integer', 'exists:storage_capacities,id'],
            'purchase_source_id' => ['nullable', 'integer', 'exists:purchase_sources,id'],

            // Konum
            'province_id' => ['required', 'integer', 'exists:provinces,id'],
            'district_id' => [
                'required',
                'integer',
                Rule::exists('districts', 'id')->where('province_id', $this->province_id)
            ],

            // Tamir/hizmet tipi
            'preferred_repair_type' => [
                'nullable',
                'string',
                Rule::in(['on_site', 'cargo', 'store_drop']),
            ],
        ];
    }

    /**
     * Modelde kullanılacak temizlenmiş veriyi döndürür.
     */
    public function validated($key = null, $default = null): array
    {
        $validatedData = parent::validated();

        // Otomatik olarak user_id'yi ekle
        $validatedData['user_id'] = Auth::id();

        // Gizli veya ilanla doğrudan ilişkili olmayan alanları kaldır
        unset($validatedData['temp_image_paths']);
        unset($validatedData['images']);

        return $validatedData;
    }

    /**
     * Service katmanına göndermek için geçici görsel yollarını döndürür.
     */
    public function getTempImagePaths(): array
    {
        return $this->input('temp_image_paths', []);
    }
}
