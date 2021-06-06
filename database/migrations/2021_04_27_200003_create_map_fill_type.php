<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMapFillType extends Migration
{
    /**
     * Information about mod map non standard fill types from map config folder and fillTypes.xml file
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fs_map_fill_type_dim', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('map_id'); // id from fs_map_dim            
            // end of indexes            
            $table->string('name',50);
            $table->boolean('price_table');
            $table->float('price_per_liter');
            $table->float('mass_per_liter');
            // system colulmns
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            // define indexes
            $table->foreign('map_id')->references('id')->on('fs_map_dim')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fs_map_fill_type_dim');
    }
}
