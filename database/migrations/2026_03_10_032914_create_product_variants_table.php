<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('color_id')->nullable(); // কালার নাও থাকতে পারে
            $table->unsignedBigInteger('size_id')->nullable();  // সাইজ নাও থাকতে পারে
            $table->unsignedBigInteger('status_id')->default(1); // ভেরিয়েন্ট স্ট্যাটাস (Active/Inactive)

            $table->string('sku')->unique();
            $table->decimal('price', 10, 2)->default(0.00); // ভেরিয়েন্ট প্রাইস
            $table->integer('stock')->default(0);           // ভেরিয়েন্ট স্টক

            $table->timestamps();
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
