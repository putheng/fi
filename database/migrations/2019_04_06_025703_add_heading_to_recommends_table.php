<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHeadingToRecommendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recommends', function (Blueprint $table) {
            $table->string('heading')->nullable();
            $table->string('heading_en')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recommends', function (Blueprint $table) {
            $table->dropColumn('heading', 'heading_en');
        });
    }
}
