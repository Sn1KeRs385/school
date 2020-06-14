<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_lessons', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('class_semester_id');
            $table->foreign('class_semester_id')
                ->references('id')
                ->on('class_semesters');

            $table->text('homework');
            $table->timestamp('lesson_begin_at')
                ->nullable();
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
        Schema::dropIfExists('class_lessons');
    }
}
