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
        Schema::create('sellers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('user_id');
            $table->string('shop_name')->unique();
            $table->string('shop_domain')->unique();
            $table->string('province')->nullable();
            $table->string('regencies')->nullable();
            $table->string('district')->nullable();
            $table->string('villages')->nullable();
            $table->string('street_name')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('slogan')->nullable();
            $table->string('desc')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sellers');
    }
};
