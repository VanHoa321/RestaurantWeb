<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('invoice_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade');
            $table->bigInteger('item_id')->nullable();
            $table->bigInteger('combo_id')->nullable();
            $table->integer('quantity')->nullable();
            $table->double('price')->nullable();
            $table->double('amount')->nullable();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_details');
    }
};
