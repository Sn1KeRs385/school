<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name')->nullable();
            $table->string('link')->nullable();
            $table->string('original_filename')->nullable();
            $table->string('type')->nullable();

            $table->string('owner_type');
            $table->integer('owner_id')->unsigned();

            $table->string('collection_name')->nullable();

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
        Schema::dropIfExists('media');
    }
}
