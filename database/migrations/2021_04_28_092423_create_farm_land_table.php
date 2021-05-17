<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmLandTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fs_farm_land', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('save_id');              
            $table->unsignedTinyInteger('farm_id');            
            $table->unsignedTinyInteger('land_id');            
            // end of indexes section
            // system colulmns            
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            // define indexes
            $table->index('save_id');
            $table->index('farm_id');
            $table->index('land_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fs_farm_land');
    }
}
