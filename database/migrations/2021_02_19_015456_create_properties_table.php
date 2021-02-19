<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->nullable();
            $table->string('county_name', 100);
            $table->string('country_name', 60);
            $table->string('town_name', 100);
            $table->text('description');
            $table->string('address', 100);
            $table->string('image', 100);
            $table->string('thumbnail', 100);
            $table->double('latitude', 3);
            $table->double('longitude', 3);
            $table->smallInteger('number_of_bedrooms');
            $table->index(['number_of_bedrooms']);
            $table->smallInteger('number_of_bathrooms');
            $table->double('price');
            $table->integer('property_type_id');
            $table->tinyInteger('sale_or_rent_type');
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
        Schema::dropIfExists('properties');
    }
}
