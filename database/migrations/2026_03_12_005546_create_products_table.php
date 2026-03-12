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
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('brand_id');
            $table->unsignedBigInteger('status_id')->default(1);
            $table->string('name', 200);
            $table->string('slug', 220)->unique();
            $table->text('description')->nullable();

            // base_price শুধুমাত্র একবার লিখুন এবং ডিফল্ট ০ দিন
            $table->decimal('base_price', 10, 2)->default(0.00);

            $table->timestamps(); // created_at and updated_at

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
