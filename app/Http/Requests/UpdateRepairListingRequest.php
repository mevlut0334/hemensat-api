<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateRepairListingRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Yetkilendirme kontrolü: Sadece ilanı oluşturan kullanıcı güncelleme yapabilir.
        // Bu kontrolü Controller'da (Policy/Service kullanarak) veya burada yapabilirsiniz.
        return Auth::check();
    }

    public function rules(): array
    {
        // Güncelleme işleminde çoğu alan nullable olabilir, ancak mantıksal olarak
        // güncellenecek alanları belirleyelim. 'id' alanının kendisi hariç.

        $rules = [
            'title' => ['sometimes', 'string', 'max:150'],
            'description' => ['sometimes', 'string', 'max:5000'],
            'is_urgent' => ['nullable', 'boolean'],
            'phone' => 'required|string|max:20|regex:/^[\+]?[0-9\s\-\(\)]+$/',

            'brand_id' => ['sometimes', 'integer', 'exists:brands,id'],
            'device_model_id' => ['sometimes', 'integer', 'exists:device_models,id'],
            'storage_capacity_id' => ['nullable', 'integer', 'exists:storage_capacities,id'],

            // Konum: İkisi birlikte güncellenmeli.
            'province_id' => ['sometimes', 'integer', 'exists:provinces,id'],
            'district_id' => [
                'sometimes',
                'integer',
                Rule::exists('districts', 'id')->where('province_id', $this->province_id)
            ],

            'preferred_repair_type' => [
                'sometimes',
                'string',
                Rule::in(['on_site', 'cargo', 'store_drop']),
            ],
        ];

        // Geçici görseller, güncellemede yeni görseller eklenecekse kullanılır.
        if ($this->has('temp_image_paths')) {
            $rules['temp_image_paths'] = ['nullable', 'array', 'max:5'];
            $rules['temp_image_paths.*'] = ['string'];
        }

        return $rules;
    }

    // ... getTempImagePaths ve validated metotları StoreRequest'teki gibi uyarlanabilir.
}
