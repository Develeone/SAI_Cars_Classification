<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarsClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('range_value', function (Blueprint $table) {
            $table->increments('id');
            $table->float('min');
            $table->float('max');
        });

        Schema::create('cars_classes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');

            // Свойства
            $table->integer('car_price_range_id')->unsigned();
            $table->foreign('car_price_range_id')->references('id')->on('range_value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cars_classes');
        Schema::dropIfExists('range_value');
    }
}
