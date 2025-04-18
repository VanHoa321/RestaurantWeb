<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['user_name', 'password']); 

            $table->string('google_id')->unique()->after('id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('user_name', 50)->nullable();
            $table->string('password', 255)->nullable();
            $table->dropColumn(['google_id', 'created_at', 'updated_at']);
        });
    }
};
