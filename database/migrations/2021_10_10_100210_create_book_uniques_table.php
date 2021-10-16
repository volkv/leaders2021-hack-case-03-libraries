<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookUniquesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_uniques', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('isbn')->index()->nullable();
            $table->unsignedInteger('year')->nullable();
            $table->text('title')->index();
            $table->text('annotation')->nullable();
            $table->text('cover_url')->nullable();
            $table->unsignedBigInteger('rubric_id')->nullable();
            $table->foreign('rubric_id')->references('id')->on('rubrics');
            $table->unsignedBigInteger('author_id')->nullable();
            $table->foreign('author_id')->references('id')->on('authors');
            $table->text('unique-title')->unique();
            $table->boolean('is_book_jsn')->default(0);
            $table->unique(['title','author_id']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('book_uniques');
    }
}
