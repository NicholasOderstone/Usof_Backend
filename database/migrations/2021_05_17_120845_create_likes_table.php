<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->enum('entity', ['post', 'comment']);
            $table->integer('entity_id')->unsigned();
            // $table->enum('type', ['like', 'dislike']); 
            $table->timestamps();
        });
        Schema::table('likes', function (Blueprint $table) {
            $table->string('author');
            $table->foreign('author')->references('name')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('likes');
    }
}
