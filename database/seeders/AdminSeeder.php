<?php

// database/seeders/AdminSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Insert an admin record with a hashed password
        Admin::create([
            'name' => 'vikash',
            'email' => 'adminvikash001@gmail.com',
            'password' => Hash::make('Admin@001'), // Hash the password
        ]);
    }
}
