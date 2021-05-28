<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UsersDepartment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('department_list_users_list', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('users_list_id');
            $table->unsignedBigInteger('department_list_id');
            $table->foreign('users_list_id')->references('id')->on('users');
            $table->foreign('department_list_id')->references('id')->on('departmentList');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_department');
    }
}
