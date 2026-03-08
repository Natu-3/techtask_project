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
        Schema::create('post_images', function (Blueprint $table){
            $table->id();
            $table->foreignId('post_id')->constrained()->onDelete('cascade');
            $table->string('path'); //저장 경로
            $table->string('original_name'); //원본 파일명
            $table->string('mime_type'); //파일의 MIME 타입
            $table->unsignedBigInteger('size'); //파일크기
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_images');
    }
};
