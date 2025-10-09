<?php


namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateImageRequest extends FormRequest
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
            "is_primary" => ["sometimes", "required", "boolean"],
            "order" => ["sometimes", "required", "integer", "min:0"],
            "status" => ["sometimes", "required", "string", "in:active,processing,failed,deleted"],
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
            "is_primary.required" => "Birincil resim durumu gereklidir.",
            "is_primary.boolean" => "Birincil resim durumu true/false olmalıdır.",
            "order.required" => "Sıralama değeri gereklidir.",
            "order.integer" => "Sıralama değeri tam sayı olmalıdır.",
            "order.min" => "Sıralama değeri negatif olamaz.",
            "status.required" => "Resim durumu gereklidir.",
            "status.in" => "Geçersiz resim durumu.",
        ];
    }
}
