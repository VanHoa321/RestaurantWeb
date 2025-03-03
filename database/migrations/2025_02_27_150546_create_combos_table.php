<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('combos', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->nullable(false);
            $table->integer('price')->nullable(false);
            $table->integer('cost_price')->default(0);
            $table->string('avatar',255)->nullable(false);
            $table->boolean('is_active')->default(true);
            $table->string('description',255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('combos');
    }
};
