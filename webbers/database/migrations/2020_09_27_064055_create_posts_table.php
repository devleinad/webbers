<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->references('id')->on('users');
            $table->string('post_title');
            $table->text('post_body');
            $table->string('identifier')->unique();
            $table->string('slug')->unique();
            $table->string("post_category");
            $table->enum("post_status", ["answered", "unanswered"]);
            $table->timestamps();

            $table->index('user_id');
            $table->index('post_category');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}