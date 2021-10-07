<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->json('points');
            $table->json('buses');
            $table->string('route_name');
            $table->string('route_number');


        });

        Schema::create('stops', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->json('points');
            $table->string('stop_name');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('routes');
        Schema::dropIfExists('stops');
    }
}
