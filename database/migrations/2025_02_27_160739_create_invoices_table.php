<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id')->nullable(false);
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->foreignId('table_id')->constrained('tables')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->dateTime('created_date')->nullable();
            $table->dateTime('time_in')->nullable();
            $table->dateTime('time_out')->nullable();
            $table->double('total_cost')->nullable(false);
            $table->double('total_amount')->nullable(false);
            $table->string('payment_method', 50)->nullable();
            $table->dateTime('payment_time')->nullable();
            $table->integer('status')->nullable(0);
            $table->string('note',255)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
