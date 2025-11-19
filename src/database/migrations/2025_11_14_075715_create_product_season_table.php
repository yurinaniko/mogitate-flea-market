<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_season', function (Blueprint $table) {
            $table->id();
            // 外部キー（products.id）
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')
                    ->references('id')
                    ->on('products')
                    ->onDelete('cascade');
            // 外部キー（seasons.id）
            $table->unsignedBigInteger('season_id');
            $table->foreign('season_id')
                    ->references('id')
                    ->on('seasons')
                    ->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_season');
    }
};