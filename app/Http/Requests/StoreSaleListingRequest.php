<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth; // Auth sınıfını ekledik

class StoreSaleListingRequest extends FormRequest
{
    /**
     * Kullanıcının bu isteği yapmaya yetkili olup olmadığını belirler.
     */
    public function authorize()
    {
        // İlan oluşturmak için kullanıcının giriş yapmış olması gerekir.
        return Auth::check();
    }

    /**
     * İstek için geçerli doğrulama kurallarını alır.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            // === TEMEL İLAN BİLGİLERİ ===
            "title" => ['required', 'string', 'max:255'],
            "description" => ['required', 'string', 'max:5000'],
            "price" => ['nullable', 'numeric', 'min:0'], // Fiyat kuralı eklendi/düzenlendi
            'phone' => 'required|string|max:20|regex:/^[\+]?[0-9\s\-\(\)]+$/',

            // === CİHAZ VE KONUM BİLGİLERİ (IDs) ===
            "brand_id" => ['required', 'exists:brands,id'],
            "model_id" => ['required', 'exists:models,id'],
            "storage_capacity_id" => ['required', 'exists:storage_capacities,id'],
            "purchase_source_id" => ['required', 'exists:purchase_sources,id'],

            // 🚨 GÜVENLİK İYİLEŞTİRMESİ: Konum Doğrulaması (Repair ile aynı mantık)
            "province_id" => ['required', 'exists:provinces,id'],
            "district_id" => [
                'required',
                'exists:districts,id',
                // CRITICAL: İlçe ID'sinin, gönderilen il ID'sine bağlı olup olmadığını kontrol et
                Rule::exists('districts', 'id')->where('province_id', $this->province_id)
            ],

            // === DURUM VE ZAMAN BİLGİLERİ ===
            "status" => ['nullable', 'string', Rule::in(['draft', 'active', 'inactive', 'sold'])],
            "published_at" => ['nullable', 'date'],
            "expires_at" => ['nullable', 'date', 'after:published_at'],

            // === RESİMLER ===
            "images" => ['nullable', 'array', 'max:3'], // en fazla 3 resim
            // images.* kuralı array içinde daha okunaklı yazıldı
            "images.*" => ['required_with:images', 'file', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:10240'],
        ];
    }

    /**
     * Get the custom error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            // Temel Bilgiler
            "title.required" => "İlan başlığı zorunludur.",
            "title.max" => "İlan başlığı en fazla 255 karakter olabilir.",
            "description.required" => "İlan açıklaması zorunludur.",
            "description.max" => "İlan açıklaması en fazla 5000 karakter olabilir.",
            "price.required" => "Fiyat bilgisi zorunludur.",
            "price.numeric" => "Fiyat geçerli bir sayı olmalıdır.",
            "price.min" => "Fiyat sıfırdan büyük olmalıdır.",


            // Cihaz Bilgileri
            "brand_id.required" => "Marka seçimi zorunludur.",
            "brand_id.exists" => "Seçilen marka geçerli değildir.",
            "model_id.required" => "Model seçimi zorunludur.",
            "model_id.exists" => "Seçilen model geçerli değildir.",
            "storage_capacity_id.required" => "Depolama kapasitesi seçimi zorunludur.",
            "storage_capacity_id.exists" => "Seçilen depolama kapasitesi geçerli değildir.",
            "purchase_source_id.required" => "Satın alma kaynağı seçimi zorunludur.",
            "purchase_source_id.exists" => "Seçilen satın alma kaynağı geçerli değildir.",

            // Konum Bilgileri
            "province_id.required" => "İl seçimi zorunludur.",
            "province_id.exists" => "Seçilen il geçerli değildir.",
            "district_id.required" => "İlçe seçimi zorunludur.",
            // district_id.exists kuralına özel mesaj eklenmedi çünkü Rule::exists kuralı
            // başarısız olursa genel 'Seçilen ilçe geçerli değildir.' mesajı yeterli olacaktır.

            // Durum ve Zaman Bilgileri
            "status.in" => "Geçersiz ilan durumu seçimi yapıldı. (İzin verilenler: draft, active, inactive, sold)",
            "expires_at.after" => "Bitiş tarihi, yayınlanma tarihinden sonra olmalıdır.",

            // Resimler
            "images.max" => "En fazla 3 adet resim yükleyebilirsiniz.",
            "images.*.mimes" => "Resim formatı jpeg, png, jpg, gif veya webp olmalıdır.",
            "images.*.max" => "Her bir resim dosyası boyutu en fazla 10MB (10240 KB) olabilir.",
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // Fiyatı ondalıklı sayıya dönüştür (virgüllü girişleri düzelt)
        if ($this->has("price")) {
            $this->merge([
                "price" => str_replace(",", ".", $this->price)
            ]);
        }

        // Varsayılan durumu ayarla
        if (!$this->has("status")) {
            $this->merge([
                "status" => "draft"
            ]);
        }

        // Auth kontrolünü authorize() metodunda yaptığımız için, burada user_id eklemeye gerek yok.
    }
}
