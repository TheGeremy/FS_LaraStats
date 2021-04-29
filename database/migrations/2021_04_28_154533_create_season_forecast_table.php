<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeasonForecastTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fs_season_forecast', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('save_id');
            $table->unsignedSmallInteger('season_day'); // original day
            $table->unsignedTinyInteger('season');            
            // end of indexes
            $table->double('average_period_temp', 16, 6)->default(0);  
            $table->unsignedInteger('windSpeed')->default(0);
            $table->unsignedInteger('windType')->default(0);
            $table->unsignedInteger('forecastType')->default(0);
            $table->double('p')->default(0);
            $table->double('startTimeIndication')->default(0);
            $table->double('cloudCover')->default(0);
            $table->double('lowTemp')->default(0);
            $table->double('highTemp')->default(0);
            $table->double('tempUncertainty')->default(0);
            $table->double('windUncertainty')->default(0);
            $table->double('precipitationUncertainty')->default(0);
            $table->double('weatherTypeUncertainty')->default(0);
            // system colulmns
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            // define indexes
            $table->index('save_id');
            $table->index('season');
            $table->index('season_day');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fs_season_forecast');
    }
}
