<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('last_name')
                ->nullable();
            $table->string('first_name')
                ->nullable();
            $table->string('patronymic')
                ->nullable();
                
            $table->string('login')
                ->nullable()
                ->unique();
                
            $table->string('email')
                ->nullable()
                ->unique();

            $table->string('password')
                ->nullable();

            $table->string('phone')
                ->nullable()
                ->unique();

            $table->timestamp('birth_date')
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
        Schema::dropIfExists('users');
    }
}
