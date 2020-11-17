<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Systemconfig extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Systemconfig', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('Server_ip');
            $table->text('thermal_ip');
            $table->text('hiface_ip');
            $table->text('username');
            $table->text('password');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
