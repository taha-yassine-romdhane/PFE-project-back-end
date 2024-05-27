<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagesTable extends Migration
{
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pdf_id'); // Ensure this matches the pdf_files table's id column
            $table->string('path');
            $table->integer('page_number');
            $table->timestamps();

            // Define the foreign key constraint
            $table->foreign('pdf_id')->references('id')->on('pdf_files')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('images');
    }
}
