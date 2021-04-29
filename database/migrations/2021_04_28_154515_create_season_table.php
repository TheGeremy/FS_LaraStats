<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeasonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fs_season', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('save_id');
            $table->unsignedTinyInteger('season');
            // end of indexes
            $table->unsignedSmallInteger('current_day_offset')->default(0);
            $table->double('days_per_season', 16, 6)->default(0);
            $table->double('soil_temp', 16, 6)->default(0);
            $table->double('soil_temp_max', 16, 6)->default(0);
            $table->double('high_temp_prev', 16, 6)->default(0);
            $table->double('crop_moisture_content', 16, 6)->default(0);
            $table->double('soil_water_content', 16, 6)->default(0);
            $table->double('low_air_temp', 16, 6)->default(0);
            $table->double('snow_depth', 16, 6)->default(0);
            $table->double('rot_dry_factor', 16, 6)->default(0);
            $table->double('average_soil_water_content', 16, 6)->default(0);
            $table->boolean('moisture_enabled')->default(1);
            // system colulmns
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            // define indexes
            $table->index('save_id');
            $table->index('season');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fs_season');
    }
}
