<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSavegameDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fs_savegame_datail', function (Blueprint $table) {
            $table->id();        
            $table->unsignedBigInteger('save_id');
            // end of indexes section
            $table->boolean('is_plant_withering_enabled'); 
            $table->boolean('traffic_enabled');
            $table->boolean('stop_and_go_braking');
            $table->boolean('automatic_motor_start_enabled');
            $table->boolean('fruit_destruction');
            $table->boolean('plowing_required_enabled');
            $table->boolean('weeds_enabled');
            $table->boolean('lime_required'); 
            $table->boolean('fuel_usage_low');
            $table->boolean('helper_buy_fuel');
            $table->boolean('helper_buy_seeds');
            $table->boolean('helperBuyFertilizer');
            $table->unsignedTinyInteger('helper_slurry_source');
            $table->unsignedTinyInteger('helper_manure_source');
            $table->unsignedTinyInteger('difficulty');
            $table->unsignedTinyInteger('economic_difficulty');
            $table->unsignedTinyInteger('plant_growth_rate');
            $table->unsignedTinyInteger('dirt_interval');
            $table->double('time_scale', 16, 6);
            $table->double('auto_save_interval', 16, 6);
            // system colulmns
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            // define indexes
            $table->index('save_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fs_savegame_detail');
    }
}
