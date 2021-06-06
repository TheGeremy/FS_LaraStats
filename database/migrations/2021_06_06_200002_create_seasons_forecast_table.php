<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeasonsForecastTable extends Migration
{
    /**
     * Holds seasons forecast information from seasons.xml file of savegame folder
     *
     * @return void
     */
    public function up()
    {
        $tableName = 'fs_seasons_forecast';

        Schema::create($tableName, function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seasons_id');
            // end of indexes section
            $table->unsignedInteger('day');
            $table->unsignedTinyInteger('season');
            $table->double('average_temp', 16, 6);
            $table->double('p', 16, 6);
            $table->double('start_time', 16, 6);
            $table->double('wind_speed', 16, 6);
            $table->unsignedTinyInteger('wind_type');
            $table->unsignedTinyInteger('type');
            $table->double('cloud_cover', 16, 6);
            $table->double('low_temp', 16, 6);
            $table->double('high_temp', 16, 6);
            $table->double('temp_uncertainty', 16, 6);
            $table->double('wind_uncertainty', 16, 6);
            $table->double('rain_uncertainty', 16, 6);
            $table->double('type_uncertainty', 16, 6);
            // system colulmns            
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            // define indexes
            $table->foreign('seasons_id')->references('id')->on('fs_seasons')->onDelete('cascade')->onUpdate('cascade');
        });

        DB::statement("ALTER TABLE `$tableName` comment 'Holds seasons forecast information from seasons.xml file of savegame folder'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fs_seasons_forecast');
    }
}
