<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'province_id' => $this->province_id,  // ← YENİ EKLENEN
            'district_id' => $this->district_id,  // ← YENİ EKLENEN
            'province' => $this->province ? $this->province->name : null,
            'district' => $this->district ? $this->district->name : null,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),


        ];
    }
}

//'province_id' => $this->province_id,  // ← YENİ EKLENEN
//'district_id' => $this->district_id,  // ← YENİ EKLENEN
