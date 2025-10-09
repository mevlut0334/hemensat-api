<?php


namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSaleListingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Yetkilendirme controller içinde yapılacak
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "title" => ["sometimes", "required", "string", "max:255"],
            "description" => ["sometimes", "required", "string", "max:5000"],
            "price" => ["sometimes", "required", "numeric", "min:0"],
            "brand_id" => ["sometimes", "required", "exists:brands,id"],
            "model_id" => ["sometimes", "required", "exists:device_models,id"],
            "storage_id" => ["sometimes", "required", "exists:storage_capacities,id"],
            "purchase_source_id" => ["nullable", "exists:purchase_sources,id"],
            "province_id" => ["sometimes", "required", "exists:provinces,id"],
            "district_id" => ["sometimes", "required", "exists:districts,id"],
            "status" => ["sometimes", "required", "string", Rule::in(["draft", "active", "inactive", "sold"])],
            "published_at" => ["nullable", "date"],
            "expires_at" => ["nullable", "date", "after:published_at"],
            "images" => ["nullable", "array"],
            "images.*" => ["required_with:images", "file", "image", "max:10240"], // 10MB max
            "deleted_images" => ["nullable", "array"],
            "deleted_images.*" => ["required_with:deleted_images", "integer", "exists:images,id"],
            'phone' => 'required|string|max:20|regex:/^[\+]?[0-9\s\-\(\)]+$/',
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
            "title.required" => "İlan başlığı gereklidir.",
            "title.max" => "İlan başlığı en fazla 255 karakter olabilir.",
            "description.required" => "İlan açıklaması gereklidir.",
            "description.max" => "İlan açıklaması en fazla 5000 karakter olabilir.",
            "price.required" => "Fiyat bilgisi gereklidir.",
            "price.numeric" => "Fiyat sayısal bir değer olmalıdır.",
            "price.min" => "Fiyat negatif olamaz.",
            "brand_id.required" => "Marka seçimi gereklidir.",
            "brand_id.exists" => "Seçilen marka geçersiz.",
            "model_id.required" => "Model seçimi gereklidir.",
            "model_id.exists" => "Seçilen model geçersiz.",
            "storage_id.required" => "Depolama seçimi gereklidir.",
            "storage_id.exists" => "Seçilen depolama kapasitesi geçersiz.",
            "purchase_source_id.exists" => "Seçilen satın alma kaynağı geçersiz.",
            "province_id.required" => "İl seçimi gereklidir.",
            "province_id.exists" => "Seçilen il geçersiz.",
            "district_id.required" => "İlçe seçimi gereklidir.",
            "district_id.exists" => "Seçilen ilçe geçersiz.",
            "status.in" => "Geçersiz ilan durumu.",
            "published_at.date" => "Yayınlanma tarihi geçerli bir tarih olmalıdır.",
            "expires_at.date" => "Bitiş tarihi geçerli bir tarih olmalıdır.",
            "expires_at.after" => "Bitiş tarihi, yayınlanma tarihinden sonra olmalıdır.",
            "images.*.image" => "Yüklenen dosya bir resim olmalıdır.",
            "images.*.max" => "Resim boyutu en fazla 10MB olabilir.",
            "deleted_images.*.exists" => "Silmek istediğiniz resim bulunamadı.",
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        // Fiyatı ondalıklı sayıya dönüştür
        if ($this->has("price")) {
            $this->merge([
                "price" => str_replace(",", ".", $this->price)
            ]);
        }
    }
}
