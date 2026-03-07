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
            $table->unsignedBigInteger('status_id');
            $table->string('name', 200);
            $table->string('slug', 220)->unique();
            $table->text('description')->nullable();
            $table->decimal('base_price', 10, 2);
            $table->timestamps(); // created_at and updated_at

             // Foreign keys
            // $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            // $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
            // $table->foreign('status_id')->references('id')->on('product_statuses')->onDelete('cascade');
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
