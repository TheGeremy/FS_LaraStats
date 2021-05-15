<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSavegameGreatDemandTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fs_savegame_great_demand', function (Blueprint $table) {
            $table->id();        
            $table->unsignedBigInteger('save_id');
            // end of indexes section
            $table->unsignedInteger('item_id');
            $table->string('fill_type', 50);
            $table->float('demand_multiplier');
            $table->unsignedInteger('demand_start_day');
            $table->unsignedInteger('demand_start_hour');
            $table->unsignedTinyInteger('demand_duration'); // in hours
            $table->boolean('is_running');
            $table->boolean('is_valid');
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
        Schema::dropIfExists('fs_savegame_great_demand');
    }
}
