<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('slides', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100)->nullable();
            $table->string('sub_title', 100)->nullable();
            $table->string('alias', 255)->nullable();
            $table->string('image', 255);
            $table->integer('order')->nullable();
            $table->tinyInteger('is_active')->default(1);
            $table->string('description', 255)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('slides');
    }
};
