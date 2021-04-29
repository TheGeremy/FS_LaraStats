<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellingStationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fs_selling_station', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('save_id');
            $table->unsignedTinyInteger('farm_id'); 
            $table->unsignedSmallInteger('sell_st_id');            
            // end of indexes            
            $table->string('class_name',100);
            $table->string('mod_name',100);
            $table->string('filename',100);
            $table->string('map_bound_id',100);
            $table->string('name',100);
            $table->boolean('bought_with_farm_land');
            // system colulmns
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            // define indexes
            $table->index('save_id');
            $table->index('farm_id');
            $table->index('sell_st_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fs_selling_station');
    }
}
