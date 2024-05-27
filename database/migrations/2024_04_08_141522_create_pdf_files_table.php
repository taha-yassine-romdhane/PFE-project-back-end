<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePdfFilesTable extends Migration
{
    public function up()
    {
        Schema::create('pdf_files', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->text('file_data')->nullable();
            $table->integer('folder_id')->nullable();

            $table->string('file_path');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pdf_files');
    }
}

