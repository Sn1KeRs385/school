<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassSemestersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_semesters', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('class_id');
            $table->foreign('class_id')
                ->references('id')
                ->on('classes');

            $table->unsignedInteger('schedule_type_id');
            $table->foreign('schedule_type_id')
                ->references('id')
                ->on('schedule_types');


            $table->timestamp('semester_begin_at')
                ->nullable();
            $table->timestamp('semester_end_at')
                ->nullable();
            $table->integer('lesson_time');
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
        Schema::dropIfExists('class_semesters');
    }
}
