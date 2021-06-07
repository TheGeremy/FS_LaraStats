<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSavegameMissionTable extends Migration
{
    /**
     * Information about missions of each savegame from savegame folder and missions.xml file
     *
     * @return void
     */
    public function up()
    {
        $tableName = 'fs_savegame_mission';

        Schema::create($tableName, function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('save_id');
            // end of indexes
            $table->string('type', 50);
            $table->double('reward', 16, 6);
            $table->unsignedSmallInteger('status_id');                                                      // 1 - free, 2 - contracted
            $table->boolean('success');
            $table->unsignedTinyInteger('field_id')->nullable();                                            // not field missions, don't have field
            $table->double('rental', 16, 6)->nullable();                                                    // some missions don't have rental option
            $table->string('fruit_type', 50)->nullable();                                                   // only those missions that are able to specify fruit type
            $table->unsignedBigInteger('farm_id')->nullable()->comment('contacted only');                   // only contracted missions
            $table->unsignedTinyInteger('active_id')->nullable()->comment('contacted only');                // only contracted missions, original game farm id >> game_id
            $table->float('stealing_cost', 16, 6)->nullable()->comment('contacted only');                   // only contracted missions
            $table->unsignedInteger('sell_point')->nullable();                                              // only harvest and mow_bale missions
            $table->double('expected_liters', 16, 6)->nullable()->comment('harvest / mow bale');            // only harvest missions
            $table->double('deposited_liters', 16, 6)->nullable()->comment('harvest');                      // only harvest and mow_bale missions
            $table->string('bale_type', 50)->nullable()->comment('harvest / mow bale');                     // only mow_bale missions
            $table->double('time_left', 16, 6)->nullable()->comment('transport');                           // only transport missions
            $table->string('trans_product', 50)->nullable()->comment('transport');                          // only transport missions
            $table->string('trans_from', 50)->nullable()->comment('transport');                             // only transport missions
            $table->string('trans_to', 50)->nullable()->comment('transport');                               // only transport missions
            $table->unsignedTinyInteger('trans_amount')->nullable()->comment('transport');                  // only transport missions
            // system colulmns
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            // define indexes
            $table->index('farm_id');
            $table->foreign('save_id')->references('id')->on('fs_savegame')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('status_id')->references('id')->on('fs_mission_status')->onDelete('restrict')->onUpdate('restrict');
        });

        DB::statement("ALTER TABLE `$tableName` comment 'Information about missions of each savegame from savegame folder and missions.xml file'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fs_savegame_mission');
    }
}
