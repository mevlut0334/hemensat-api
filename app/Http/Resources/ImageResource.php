<?php


namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ImageResource extends JsonResource
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
            "sale_listing_id" => $this->sale_listing_id,
            "path" => $this->path,
            "filename" => $this->filename,
            "original_name" => $this->original_name,
            "size" => $this->size,
            "formatted_size" => $this->formatted_size,
            "mime_type" => $this->mime_type,
            "extension" => $this->extension,
            "file_type" => $this->file_type,
            "is_image" => $this->is_image,
            "width" => $this->width,
            "height" => $this->height,
            "order" => $this->order,
            "is_primary" => (bool) $this->is_primary,
            "status" => $this->status,
            "url" => $this->url,
            "thumbnail_url" => $this->thumbnail_url,
            "created_at" => $this->created_at->format("Y-m-d H:i:s"),
            "updated_at" => $this->updated_at->format("Y-m-d H:i:s"),
        ];
    }
}
