<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('restaurant_info', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->nullable(false);
            $table->string('hotline_1', 15)->nullable();
            $table->string('hotline_2', 15)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('opening_day', 50)->nullable();
            $table->time('open_time')->nullable(false);
            $table->time('close_time')->nullable(false);
            $table->string('sort_description', 255)->nullable();
            $table->text('log_description')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restaurant_info');
    }
};
