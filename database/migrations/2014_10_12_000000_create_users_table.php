<?php

use App\Models\User;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('is_admin')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });

        User::create([
            'name' => 'Steven Tringali',
            'email' => 'dr.tringali@gmail.com',
            'password' => '$2y$10$0pnUxjVBkGt09H4Sc.vEOeFj51i7.1rRoxLpB/SSb8K7YV4gULQlK',
            'is_admin' => 1,
            'remember_token' => 'hYN7Bt7LQsw7zic03BimrK4qJ2mv3g87cN8rjw0YHU5lBUJK6yBO5IsBJwzF',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
