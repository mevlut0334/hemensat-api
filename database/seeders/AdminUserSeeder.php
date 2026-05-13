<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'        => 'Admin',
            'email'       => 'admin@hemensat.com',
            'password'    => Hash::make('271369lmlm'),
            'is_admin'    => true,
            'province_id' => 1,
            'district_id' => 1,
        ]);

        $this->command->info('✅ Admin kullanıcı oluşturuldu.');
    }
}
