<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attribute kabul edilmelidir.',
    'active_url' => ':attribute geçerli bir URL değil.',
    // ... diğer varsayılan mesajlar

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'name' => [
            'required' => 'Ad ve soyad alanı zorunludur.',
            'string' => 'Ad ve soyad metin formatında olmalıdır.',
            'max' => 'Ad ve soyad en fazla 255 karakter olabilir.',
        ],
        'email' => [
            'required' => 'E-posta adresi zorunludur.',
            'email' => 'Lütfen geçerli bir e-posta adresi giriniz.',
            'unique' => 'Bu e-posta adresi zaten kullanılıyor.',
        ],
        'password' => [
            'required' => 'Şifre alanı zorunludur.',
            'min' => 'Şifreniz en az 8 karakter olmalıdır.',
            'confirmed' => 'Şifreler eşleşmiyor.',
        ],
        'province_id' => [
            'required' => 'İl seçimi zorunludur.',
            'exists' => 'Seçtiğiniz il geçersizdir.',
        ],
        'district_id' => [
            'required' => 'İlçe seçimi zorunludur.',
            'exists' => 'Seçtiğiniz ilçe geçersizdir.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'name' => 'ad soyad',
        'email' => 'e-posta',
        'password' => 'şifre',
        'province_id' => 'il',
        'district_id' => 'ilçe',
    ],

];
