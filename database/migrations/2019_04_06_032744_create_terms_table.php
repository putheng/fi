<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTermsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('terms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('heading');
            $table->string('heading_en');
            $table->string('bradcume');
            $table->string('bradcume_en');
            $table->string('title');
            $table->string('title_en');
            $table->text('subtitle');
            $table->text('subtitle_en');
            $table->string('term');
            $table->string('term_en');
            $table->text('note');
            $table->text('note_en');
            $table->integer('image_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('terms');
    }
}
