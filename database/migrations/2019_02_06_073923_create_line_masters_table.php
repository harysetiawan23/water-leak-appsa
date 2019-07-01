<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLineMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('line_masters', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->string('start', 100)->nullable()->default('text');
            $table->string('end', 100)->nullable()->default('text');
            $table->integer('distance')->unsigned()->nullable()->default(0);
            $table->integer('diameter')->unsigned()->nullable()->default(0);
            $table->integer('thicknes')->unsigned()->nullable()->default(0);
            $table->string('manufacture', 100)->nullable()->default('N/A');
            $table->integer('user_id')->unsigned();
            $table->integer('start_node_id')->unsigned()->nullable()->default(12);
            $table->integer('end_node_id')->unsigned()->nullable()->default(12);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('start_node_id')->references('id')->on('node_masters')->onDelete('cascade');
            $table->foreign('end_node_id')->references('id')->on('node_masters')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('line_masters');
    }
}
