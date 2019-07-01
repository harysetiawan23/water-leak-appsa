<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLineEventReceiversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('line_event_receivers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('line_id')->unsigned()->nullable()->default(12);
            $table->string('name', 100);
            $table->string('email', 100);
            $table->timestamps();
            $table->foreign('line_id')->references('id')->on('line_masters')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('line_event_receivers');
    }
}
