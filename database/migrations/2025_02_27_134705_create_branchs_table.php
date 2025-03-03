<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('branchs', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->nullable(false);
            $table->string('address',255)->nullable(false);
            $table->string('phone',10)->nullable(false);
            $table->string('email',100)->nullable(false);
            $table->boolean('is_active')->default(true);
            $table->string('description',255)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branchs');
    }
};
