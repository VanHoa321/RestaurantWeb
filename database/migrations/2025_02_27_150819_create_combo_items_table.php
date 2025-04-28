<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('combo_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('combo_id')->constrained('combos')->onDelete('cascade');
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('combo_items');
    }
};
