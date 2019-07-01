<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNodeRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('node_records', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('line_id')->unsigned()->nullable()->default(12);
            $table->boolean('isStartNode')->nullable()->default(false);
            $table->float('flow')->nullable()->default(0);
            $table->float('pressure')->nullable()->default(0);
            $table->float('liters')->nullable()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('node_records');
    }
}
