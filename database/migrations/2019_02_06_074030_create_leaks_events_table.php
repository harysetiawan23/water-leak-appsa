<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeaksEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leaks_events', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('line_id')->unsigned()->nullable()->default(12);
            $table->float('flow_leak_ratio')->nullable()->default(0);
            $table->float('pressure_leak_ratio')->nullable()->default(123.45);
            $table->integer('user_id')->unsigned()->nullable()->default(12);
            $table->boolean('solved')->default(false);
            $table->boolean('informed')->nullable()->default(false);
            $table->timestamps();
            $table->foreign('line_id')->references('id')->on('line_masters');
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
        Schema::dropIfExists('leaks_events');
    }
}
