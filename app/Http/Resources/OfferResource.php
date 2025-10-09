<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
{
    /**
     * Kaynağı bir diziye dönüştürün.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'offer_price' => (float) $this->offer_price,
            'status' => $this->status, // pending, accepted, rejected
            'submitted_at' => $this->created_at->format('Y-m-d H:i:s'),

            // Sadece kabul/reddedilmişse tarihleri göster
            'accepted_at' => $this->accepted_at ? $this->accepted_at->format('Y-m-d H:i:s') : null,
            'rejected_at' => $this->rejected_at ? $this->rejected_at->format('Y-m-d H:i:s') : null,

            // Teklifi Veren Kullanıcı Bilgisi (NULL kontrolü eklendi)
            'user' => $this->user ? [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
            ] : null,

            // Teklifin Ait Olduğu İlan Bilgisi (Polimorfik - NULL kontrolü eklendi)
            'listing' => $this->offerable ? [
                'id' => $this->offerable_id,
                'type' => class_basename($this->offerable_type), // RepairListing veya SaleListing
                'title' => $this->offerable->title,
            ] : null,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
