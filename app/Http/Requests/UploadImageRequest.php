<?php


namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadImageRequest extends FormRequest
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
            "images" => ["required", "array", "max:10"], // En fazla 10 resim
            "images.*" => ["required", "file", "image", "mimes:jpeg,png,jpg,gif,webp", "max:10240"], // 10MB max
            "sale_listing_id" => ["required", "integer", "exists:sale_listings,id"],
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
            "images.required" => "En az bir resim yüklemeniz gerekmektedir.",
            "images.array" => "Resimler dizi formatında olmalıdır.",
            "images.max" => "En fazla 10 resim yükleyebilirsiniz.",
            "images.*.required" => "Tüm resim alanları gereklidir.",
            "images.*.file" => "Yüklenen dosya geçerli bir dosya olmalıdır.",
            "images.*.image" => "Yüklenen dosya bir resim olmalıdır.",
            "images.*.mimes" => "Sadece jpeg, png, jpg, gif ve webp formatındaki resimler kabul edilir.",
            "images.*.max" => "Resim boyutu en fazla 10MB olabilir.",
            "sale_listing_id.required" => "İlan ID gereklidir.",
            "sale_listing_id.integer" => "İlan ID sayısal bir değer olmalıdır.",
            "sale_listing_id.exists" => "Belirtilen ilan bulunamadı.",
        ];
    }
}
