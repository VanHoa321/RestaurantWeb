<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->dropForeign(['area_id']);
                $table->dropColumn('area_id');  
                $table->foreignId('branch_id')->after('customer_id')->constrained('branchs')->onDelete('cascade');
            });
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn('branch_id');
            $table->foreignId('area_id')->constrained('areas')->onDelete('cascade');
        });
    }
};
