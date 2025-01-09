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
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('seller_id');
            $table->string('name');
            $table->string('image_url')->nullable();
            $table->enum('condition', ['baru', 'bekas']);
            $table->text('description');
            $table->decimal('price', 15, 2)->unsigned();
            $table->unsignedInteger('stock');
            $table->string('sku')->unique()->nullable();
            $table->unsignedMediumInteger('product_weight');
            $table->enum('shipping_insurance', ['wajib', 'opsional']);
            $table->foreign('seller_id')->references('id')->on('sellers')->onDelete('cascade');
            $table->timestamp('is_active')->nullable();
            $table->softDeletes();
            $table->timestamps();
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
