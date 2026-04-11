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
        
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('product')->nullable();
            $table->longText('description')->nullable();
            $table->string('image')->nullable();
            $table->integer('price')->nullable();;
            $table->longText('category')->nullable();
            $table->string('user_id')->nullable();
            $table->string('usertype')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
