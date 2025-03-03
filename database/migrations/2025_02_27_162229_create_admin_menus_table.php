<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('admin_menus', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->nullable(false);
            $table->integer('level')->nullable(false);
            $table->integer('parent')->nullable(false);
            $table->integer('order')->nullable(false);
            $table->string('icon', 255)->nullable(false);
            $table->string('route', 50)->nullable(false);
            $table->boolean('is_active')->default(true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_menus');
    }
};
