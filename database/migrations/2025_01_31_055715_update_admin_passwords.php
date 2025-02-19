<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

return new class extends Migration
{
    public function up()
    {
        // Update existing admin passwords to use bcrypt
        $admins = Admin::all();
        foreach ($admins as $admin) {
            $admin->password = Hash::make($admin->password);
            $admin->save();
        }
    }

    public function down()
    {
        // Cannot revert password hashing
    }
}; 