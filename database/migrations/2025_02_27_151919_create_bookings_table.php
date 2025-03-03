<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id')->nullable();
            $table->foreignId('area_id')->constrained('areas')->onDelete('cascade');
            $table->integer('table_id')->nullable();
            $table->dateTime('booking_date')->nullable(false);
            $table->string('time_slot')->nullable(false);
            $table->integer('guest_count')->default(1);
            $table->integer('pre_payment')->default(0);
            $table->integer('status')->default(0);
            $table->string('note',255)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
