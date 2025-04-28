<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('user_name', 50)->nullable();
            $table->string('password', 50)->nullable();
            $table->string('full_name', 50)->nullable();
            $table->string('avatar', 50)->nullable();
            $table->string('phone', 10)->nullable()->unique();
            $table->string('email', 255)->nullable()->unique();
            $table->string('address', 255)->nullable();
            $table->dateTime('last_login')->nullable();
            $table->boolean('is_active')->default(true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
