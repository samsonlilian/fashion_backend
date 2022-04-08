<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fashions', function (Blueprint $table) {
            $table->id();
            $table->integer('gender'); // male 0  && female 1
            $table->integer('age_range'); // > 50 =  1    && < 50  = 0
            $table->string('title');
            $table->string('keywords');
            $table->string('cover_img');
            $table->string('desc');
            $table->text('desc_long');

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
        Schema::dropIfExists('fashions');
    }
};
