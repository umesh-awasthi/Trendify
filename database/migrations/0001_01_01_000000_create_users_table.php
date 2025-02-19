<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Creating the 'users' table
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique(); // Ensure unique email addresses
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        // Creating the 'password_reset_tokens' table
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email');  // Email associated with the reset
            $table->string('token');  // The reset token
            $table->timestamp('created_at')->nullable();  // When the token was created
            $table->unique('email');  // Ensure each email has a single token at a time
        });

        // Creating the 'sessions' table
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();  // Session ID
            $table->foreignId('user_id')->nullable()->constrained()->index();  // User associated with the session
            $table->string('ip_address', 45)->nullable();  // IP address for security
            $table->text('user_agent')->nullable();  // The user's browser info
            $table->longText('payload');  // Session payload data
            $table->integer('last_activity')->index();  // Timestamp of last activity
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the foreign key constraint before dropping the users table
        Schema::table('sessions', function (Blueprint $table) {
            $table->dropForeign(['user_id']);  // Drop the foreign key referencing the 'users' table
        });

        // Now drop the tables
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
