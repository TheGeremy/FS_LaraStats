<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehicleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fs_vehicle', function (Blueprint $table) {
            $table->id();        
            $table->unsignedBigInteger('save_id');
            $table->unsignedBigInteger('farm_id');
            $table->unsignedInteger('game_id');                  // original id from game, can change from save to save, just to match any connected device
            // end of indexes section
            $table->string('mod_name',100)->nullable()->comment('mods only');
            $table->string('filename',100);
            $table->unsignedTinyInteger('property_state')->default(0);            
            $table->double('age', 16, 6);
            $table->double('price', 16, 6);
            $table->double('operating_time', 16, 6);
            // vehicle >> fillUnit >> unit >> attributes (fillTYpe fillLevel)
            $table->double('diesel', 16, 6)->nullable();
            $table->double('def', 16, 6)->nullable();
            $table->double('air', 16, 6)->nullable();
            // FS19_RM_Seasons >> seasonsVehicle >> attributes 
            $table->double('seasons_years', 16, 6)->nullable();
            $table->double('seasons_next_repair', 16, 6)->nullable();
            // system colulmns
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            // define indexes
            $table->index('save_id');
            $table->index('farm_id');
            $table->index('game_id');
        });
    } 

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fs_vehicle');
    }
}
