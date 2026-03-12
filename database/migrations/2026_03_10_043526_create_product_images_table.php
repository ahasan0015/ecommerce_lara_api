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
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
             // এটি সরাসরি ভেরিয়েন্টের সাথে যুক্ত থাকবে
            $table->foreignId('product_variant_id')->constrained('product_variants')->onDelete('cascade');
            $table->string('image'); // ইমেজের পাথ (যেমন: products/abc.webp)
            $table->boolean('is_main')->default(false); // এটি কি গ্যালারির মেইন কভার ফটো?
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }
};
