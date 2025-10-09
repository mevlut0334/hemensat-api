<?php


namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StatsResource extends JsonResource
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
            "total_listings" => $this->total_listings ?? 0,
            "active_listings" => $this->active_listings ?? 0,
            "sold_listings" => $this->sold_listings ?? 0,
            "inactive_listings" => $this->inactive_listings ?? 0,
            "total_offers_received" => $this->total_offers_received ?? 0,
            "highest_offer_received" => (float) ($this->highest_offer_received ?? 0),
            "avg_offers_per_listing" => (float) ($this->avg_offers_per_listing ?? 0),

            // Image stats
            "total_images" => $this->total_images ?? 0,
            "active_images" => $this->active_images ?? 0,
            "processing_images" => $this->processing_images ?? 0,
            "failed_images" => $this->failed_images ?? 0,
            "primary_images" => $this->primary_images ?? 0,
            "total_size" => $this->total_size ?? 0,
            "avg_size" => (float) ($this->avg_size ?? 0),
            "first_upload" => $this->first_upload ? $this->first_upload->format("Y-m-d H:i:s") : null,
            "last_upload" => $this->last_upload ? $this->last_upload->format("Y-m-d H:i:s") : null,
        ];
    }
}
