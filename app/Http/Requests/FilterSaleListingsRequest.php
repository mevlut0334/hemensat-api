<?php


namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FilterSaleListingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "brand_id" => ["nullable", "integer", "exists:brands,id"],
            "model_id" => ["nullable", "integer", "exists:device_models,id"],
            "storage_id" => ["nullable", "integer", "exists:storage_capacities,id"],
            "province_id" => ["nullable", "integer", "exists:provinces,id"],
            "district_id" => ["nullable", "integer", "exists:districts,id"],
            "min_price" => ["nullable", "numeric", "min:0"],
            "max_price" => ["nullable", "numeric", "min:0", "gte:min_price"],
            "has_offers" => ["nullable", "boolean"],
            "recent_activity" => ["nullable", "integer", "min:1"],
            "sort_by" => ["nullable", "string", Rule::in(["newest", "oldest", "price_high", "price_low", "popular"])],
            "per_page" => ["nullable", "integer", "min:1", "max:100"],
            "page" => ["nullable", "integer", "min:1"],
        ];
    }

    /**
     * Get the custom error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            "brand_id.exists" => "Seçilen marka geçersiz.",
            "model_id.exists" => "Seçilen model geçersiz.",
            "storage_id.exists" => "Seçilen depolama kapasitesi geçersiz.",
            "province_id.exists" => "Seçilen il geçersiz.",
            "district_id.exists" => "Seçilen ilçe geçersiz.",
            "min_price.numeric" => "Minimum fiyat sayısal bir değer olmalıdır.",
            "min_price.min" => "Minimum fiyat negatif olamaz.",
            "max_price.numeric" => "Maksimum fiyat sayısal bir değer olmalıdır.",
            "max_price.min" => "Maksimum fiyat negatif olamaz.",
            "max_price.gte" => "Maksimum fiyat, minimum fiyattan küçük olamaz.",
            "has_offers.boolean" => "Teklif filtresi geçerli bir değer olmalıdır.",
            "recent_activity.integer" => "Son aktivite filtresi sayısal bir değer olmalıdır.",
            "recent_activity.min" => "Son aktivite filtresi 1 veya daha büyük olmalıdır.",
            "sort_by.in" => "Geçersiz sıralama seçeneği.",
            "per_page.integer" => "Sayfa başına öğe sayısı sayısal bir değer olmalıdır.",
            "per_page.min" => "Sayfa başına en az 1 öğe gösterilebilir.",
            "per_page.max" => "Sayfa başına en fazla 100 öğe gösterilebilir.",
            "page.integer" => "Sayfa numarası sayısal bir değer olmalıdır.",
            "page.min" => "Sayfa numarası 1 veya daha büyük olmalıdır.",
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        // Fiyatları ondalıklı sayıya dönüştür
        if ($this->has("min_price")) {
            $this->merge([
                "min_price" => str_replace(",", ".", $this->min_price)
            ]);
        }

        if ($this->has("max_price")) {
            $this->merge([
                "max_price" => str_replace(",", ".", $this->max_price)
            ]);
        }

        // Varsayılan değerleri ayarla
        if (!$this->has("sort_by")) {
            $this->merge([
                "sort_by" => "newest"
            ]);
        }

        if (!$this->has("per_page")) {
            $this->merge([
                "per_page" => 20
            ]);
        }
    }
}
