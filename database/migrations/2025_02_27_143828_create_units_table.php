<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->nullable(false);
            $table->boolean('is_active')->default(true);
            $table->string('description',255)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
