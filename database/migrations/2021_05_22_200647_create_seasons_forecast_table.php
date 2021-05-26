<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeasonsForecastTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fs_seasons_forecast', function (Blueprint $table) {
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
            $table->index('seasons_id');
        });
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
