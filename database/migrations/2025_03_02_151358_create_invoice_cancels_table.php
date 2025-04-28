<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('invoice_cancels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade');
            $table->string('cancel_reason')->nullable();
            $table->dateTime('cancel_date')->nullable();
            $table->integer('cancel_by')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_cancels');
    }
};
