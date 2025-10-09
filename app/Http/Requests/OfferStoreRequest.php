<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\SaleListing;
use App\Models\RepairListing;
use Illuminate\Validation\Rule;

class OfferStoreRequest extends FormRequest
{
    /**
     * Bu isteği yapmaya yetkili olup olmadığını belirleyin.
     * Bu işlem, kullanıcının abone olup olmadığını kontrol etmek için ideal bir yerdir.
     * Ancak, Abonelik kontrolünü Route'daki Middleware'e bırakacağız.
     */
    public function authorize(): bool
    {
        // Teklif verecek kullanıcının giriş yapmış olması yeterlidir.
        // Abonelik kontrolü (is_subscribed) middleware'de yapılacak.
        return auth()->check();
    }

    /**
     * İstek için doğrulama kurallarını alın.
     */
    public function rules(): array
    {
        // Teklifin hangi modele ait olduğunu belirten dizin
        $allowedListingTypes = [
            SaleListing::class,
            RepairListing::class
        ];

        return [
            // Zorunlu alanlar
            'offer_price' => ['required', 'numeric', 'min:0.01'],
            'offerable_type' => [
                'required',
                'string',
                // Sadece izin verilen ilan tiplerini kabul et
                Rule::in($allowedListingTypes),
            ],
            'offerable_id' => [
                'required',
                'integer',
                'min:1',
                // İlanın veritabanında var olup olmadığını kontrol et
                $this->getOfferableExistsRule(),
            ],
        ];
    }

    /**
     * Dinamik olarak exists kuralını oluşturan yardımcı metot.
     */
    protected function getOfferableExistsRule()
    {
        // Request'ten gelen ilan tipini alır.
        $offerableType = $this->input('offerable_type');

        // İlan tipi geçerli izin verilen tiplerden biriyse (örneğin App\Models\SaleListing),
        // o modelin tablosunda (sale_listings) ID'nin var olup olmadığını kontrol eder.
        if (in_array($offerableType, [SaleListing::class, RepairListing::class])) {

            // Model'den tablo adını alır (SaleListing::class -> 'sale_listings')
            $model = new $offerableType();
            $tableName = $model->getTable();

            // İlan aktif mi kontrolü eklenebilir. Örneğin: ->where('status', 'active')
            return Rule::exists($tableName, 'id');
        }

        // Eğer tip geçerli değilse, kuralı es geçmek yerine temel bir exists kuralı döndürülür (Tekrar Rule::in kontrolüne tabi tutulacağı için güvenlidir).
        return 'exists:users,id'; // Placeholder: Model bulunamadığında hata vermemesi için
    }


    /**
     * Hata mesajlarını özelleştirin (Opsiyonel).
     */
    public function messages(): array
    {
        return [
            'offer_price.required' => 'Lütfen bir teklif fiyatı giriniz.',
            'offer_price.numeric' => 'Teklif fiyatı sayısal olmalıdır.',
            'offer_price.min' => 'Teklif fiyatı 0\'dan büyük olmalıdır.',
            'offerable_type.in' => 'Geçersiz ilan tipi belirtildi.',
            'offerable_id.exists' => 'Teklif vermek istediğiniz ilan bulunamadı.',
        ];
    }
}
