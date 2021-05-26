<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeasonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fs_seasons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('save_id');
            // end of indexes section
            $table->unsignedTinyInteger('day_offset');
            $table->unsignedTinyInteger('days_per_season');
            $table->double('soil_temp', 16, 6);
            $table->double('soil_temp_max', 16, 6);
            $table->double('high_temp_prev', 16, 6);
            $table->double('crop_moisture', 16, 6);
            $table->double('water_in_soil', 16, 6);
            $table->double('air_temp_low', 16, 6);
            $table->double('snow_height', 16, 6);
            $table->double('snow_depth', 16, 6);
            $table->double('rot_dry_factor', 16, 6);
            $table->double('avg_water_in_soil', 16, 6);
            $table->boolean('moisture');
            $table->boolean('snow_tracks');
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
        Schema::dropIfExists('fs_seasons');
    }
}
