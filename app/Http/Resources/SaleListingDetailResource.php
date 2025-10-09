<?php


namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ImageResource;

class SaleListingDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "description" => $this->description,
            "status" => $this->status,
            "published_at" => $this->published_at ? $this->published_at->format("Y-m-d H:i:s") : null,
            "expires_at" => $this->expires_at ? $this->expires_at->format("Y-m-d H:i:s") : null,
            "created_at" => $this->created_at->format("Y-m-d H:i:s"),
            "updated_at" => $this->updated_at->format("Y-m-d H:i:s"),
             "phone" => $this->phone,



            // Relations
            "user" => [
                "id" => $this->user->id,
                "name" => $this->user->name,
                "created_at" => $this->user->created_at->format("Y-m-d H:i:s"),
            ],
            "brand" => [
                "id" => $this->brand->id,
                "name" => $this->brand->name,
            ],
            "model" => [
                "id" => $this->deviceModel->id,
                "name" => $this->deviceModel->name,
            ],
            "storage" => [
                "id" => $this->storageCapacity->id,
                "name" => $this->storageCapacity->name,
                "capacity" => $this->storageCapacity->capacity,
            ],
            "purchase_source" => $this->purchaseSource ? [
                "id" => $this->purchaseSource->id,
                "name" => $this->purchaseSource->name,
            ] : null,
            "location" => [
                "province" => [
                    "id" => $this->province->id,
                    "name" => $this->province->name,
                ],
                "district" => [
                    "id" => $this->district->id,
                    "name" => $this->district->name,
                ],
            ],

            // Images
            "images" => ImageResource::collection($this->whenLoaded("images")),

            // Stats
            "stats" => [
                "total_offers_count" => $this->total_offers_count ?? 0,
                "pending_offers_count" => $this->pending_offers_count ?? 0,
                "accepted_offers_count" => $this->accepted_offers_count ?? 0,
                "rejected_offers_count" => $this->rejected_offers_count ?? 0,
                "highest_offer_price" => (float) ($this->highest_offer_price ?? 0),
                "latest_offer_price" => (float) ($this->latest_offer_price ?? 0),
                "average_offer_price" => (float) ($this->average_offer_price ?? 0),
                "last_offer_at" => $this->last_offer_at ? $this->last_offer_at->format("Y-m-d H:i:s") : null,
                "images_count" => $this->when($this->relationLoaded("images"), function () {
                    return $this->images->count();
                }, 0),
            ],
        ];
    }
}
