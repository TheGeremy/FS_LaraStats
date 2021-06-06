<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmPalletTable extends Migration
{
    /**
     * Information about all details about pallets in game and relation to farm from savegame folder and vehicles.xml
     *
     * @return void
     */
    public function up()
    {
        $tableName = 'fs_farm_pallet';

        Schema::create($tableName, function (Blueprint $table) {
            $table->id();        
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
            $table->string('fill_type_1', 100)->nullable();
            $table->double('fill_level_1', 16, 6)->nullable();
            $table->string('fill_type_2', 100)->nullable();
            $table->double('fill_level_2', 16, 6)->nullable();
            $table->string('fill_type_3', 100)->nullable();
            $table->double('fill_level_3', 16, 6)->nullable();  
            // FS19_RM_Seasons >> seasonsVehicle >> attributes 
            $table->double('seasons_years', 16, 6)->nullable();
            $table->double('seasons_next_repair', 16, 6)->nullable();
            // system colulmns
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            // define indexes
            $table->foreign('farm_id')->references('id')->on('fs_farm')->onDelete('cascade')->onUpdate('cascade');
            $table->index('game_id');
        });

        DB::statement("ALTER TABLE `$tableName` comment 'Information about all details about pallets in game and relation to farm from savegame folder and vehicles.xml'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fs_farm_pallet');
    }
}
