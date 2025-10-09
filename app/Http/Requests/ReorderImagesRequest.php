<?php


namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReorderImagesRequest extends FormRequest
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
            "sale_listing_id" => ["required", "integer", "exists:sale_listings,id"],
            "image_orders" => ["required", "array"],
            "image_orders.*.image_id" => ["required", "integer", "exists:images,id"],
            "image_orders.*.order" => ["required", "integer", "min:0"],
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
            "sale_listing_id.required" => "İlan ID gereklidir.",
            "sale_listing_id.integer" => "İlan ID sayısal bir değer olmalıdır.",
            "sale_listing_id.exists" => "Belirtilen ilan bulunamadı.",
            "image_orders.required" => "Resim sıralama bilgisi gereklidir.",
            "image_orders.array" => "Resim sıralama bilgisi dizi formatında olmalıdır.",
            "image_orders.*.image_id.required" => "Resim ID gereklidir.",
            "image_orders.*.image_id.integer" => "Resim ID sayısal bir değer olmalıdır.",
            "image_orders.*.image_id.exists" => "Belirtilen resim bulunamadı.",
            "image_orders.*.order.required" => "Sıralama değeri gereklidir.",
            "image_orders.*.order.integer" => "Sıralama değeri tam sayı olmalıdır.",
            "image_orders.*.order.min" => "Sıralama değeri negatif olamaz.",
        ];
    }
}
