<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RepairListingDetailResource extends JsonResource
{
    /**
     * İlan detay verisini diziye dönüştürür.
     *
     * Bu resource sadece ilan sahibi veya abone olan kullanıcılar için kullanılır.
     * Tam detayları içerir.
     */
   public function toArray(Request $request): array
{
    $user = $request->user();
    $isOwner = $user && $user->id === $this->user_id;

    return [
        'id' => $this->id,
        'title' => $this->title,
        'description' => $this->description,

        'phone' => $this->phone,

        // Kullanıcı Bilgisi (İlan Sahibi)
        'user' => $this->whenLoaded('user', fn() => new UserResource($this->user)),

        // Cihaz Bilgisi
        'brand' => $this->whenLoaded('brand', fn() => new BrandResource($this->brand)),
        'model' => $this->whenLoaded('deviceModel', fn() => new DeviceModelResource($this->deviceModel)),
        'storage_capacity' => $this->whenLoaded('storageCapacity', fn() => new StorageCapacityResource($this->storageCapacity)),
        'purchase_source' => $this->whenLoaded('purchaseSource', fn() => new PurchaseSourceResource($this->purchaseSource)),

        // Konum Detayı
        'province' => $this->whenLoaded('province', fn() => new ProvinceResource($this->province)),
        'district' => $this->whenLoaded('district', fn() => new DistrictResource($this->district)),

        // Tüm Görsel Koleksiyonu
        'images' => ImageResource::collection($this->whenLoaded('images')),

        // TEKLİFLER - Sadece yetkili kullanıcılar görebilir
        'offers' => $this->when(
            $this->relationLoaded('offers'),
            fn() => OfferResource::collection($this->offers)
        ),

        // Durum ve Zaman Bilgileri
        'status' => $this->status,
        'is_urgent' => (bool) $this->is_urgent,
        'preferred_repair_type' => $this->preferred_repair_type,
        'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
        'published_at' => $this->published_at?->format('Y-m-d H:i:s'),
        'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),

        // İstatistik Bilgileri
        'stats' => [
            'total_offers_count' => $this->whenLoaded('offers', fn() => $this->offers->count(), 0),
            'images_count' => $this->whenLoaded('images', fn() => $this->images->count(), 0),
            'view_count' => $this->view_count ?? 0,
        ],

        // Ek Bilgiler
        'contact_info' => [
            'can_contact' => true,
            'phone_visible' => true,
        ],

        // Yetki Bilgileri
        'permissions' => [
            'is_owner' => $isOwner,
            'can_view_offers' => $this->relationLoaded('offers'),
            'can_make_offer' => $user && !$isOwner, // Abone ve sahip değilse teklif verebilir
        ],
    ];
}
}
