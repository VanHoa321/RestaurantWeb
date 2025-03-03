<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->nullable(false);
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('item_categories')->onDelete('cascade');
            $table->string('avatar',255)->nullable(false);
            $table->boolean('is_active')->default(true);
            $table->string('description',255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
