<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNodeMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('node_masters', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sn', 100)->nullable()->default('text');
            $table->string('phone_number', 100)->nullable()->default('text');
            $table->string('lat', 100)->nullable()->default('text');
            $table->string('lng', 100)->nullable()->default('text');
            $table->boolean('isStartNode')->nullable()->default(false);
            $table->integer('user_id')->unsigned()->nullable()->default(12);
            $table->boolean('isOnline')->nullable()->default(false);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('node_masters');
    }
}
