<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('tables', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->nullable(false);
            $table->foreignId('area_id')->constrained('areas')->onDelete('cascade');
            $table->foreignId('type_id')->constrained('table_types')->onDelete('cascade');
            $table->integer('status')->default(1);
            $table->boolean('is_active')->default(true);
            $table->string('description',255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tables');
    }
};
