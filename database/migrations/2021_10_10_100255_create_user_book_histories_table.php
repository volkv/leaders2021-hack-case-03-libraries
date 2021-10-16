<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserBookHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_book_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('book_id')->nullable()->index();
            $table->foreign('book_id')->references('id')->on('books');
            $table->unsignedBigInteger('user_id')->index();
            $table->index(['book_id','user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_book_histories');
    }
}
