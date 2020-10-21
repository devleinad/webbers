<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBountiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bounties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("post_id")->references('id')->on('posts');
            $table->integer("bounty_points");
            $table->enum('status', ['active', 'inactive']);
            $table->timestamps();
            $table->index('post_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bounties');
    }
}