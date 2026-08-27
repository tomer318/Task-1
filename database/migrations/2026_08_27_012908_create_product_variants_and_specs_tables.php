<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Bảng Phiên bản / Biến thể sản phẩm (Màu sắc, Dung lượng, Giá riêng)
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('version_name'); 
            $table->string('color_name');  
            $table->string('color_image')->nullable();
            $table->decimal('price', 12, 2);
            $table->integer('stock')->default(10);
            $table->string('sku')->unique()->nullable();
            $table->timestamps();
        });

        // 2. Bảng Thông số kỹ thuật chi tiết
        Schema::create('product_specifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('group_name'); 
            $table->string('spec_key');   
            $table->text('spec_value');   
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_specifications');
        Schema::dropIfExists('product_variants');
    }
};