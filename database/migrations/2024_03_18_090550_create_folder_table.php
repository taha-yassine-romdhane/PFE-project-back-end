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
        Schema::create('folder', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('folder_state');
            $table->string('file_state');
            $table->text('file')->nullable();
        //    $table->unsignedBigInteger('id_category')->nullable();
        //    $table->foreign('id_category')->references('id')->on('category_model');
        //    $table->unsignedBigInteger('id_user');
        //    $table->foreign('id_user')->references('id')->on('user');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('folder');
    }
};
