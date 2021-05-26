<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmBaleTable extends Migration
{
    /**
     * Table based on this record from items.xml
     * <item className="Bale" id="2" modName="FS19_addon_strawHarvest" 
     * isConnectedToInline="false" 
     * filename="$pdlcdir$FS19_addon_strawHarvest/objects/squareBales/squareBaleHay_8Knots.i3d" 
     * position="77.1303 91.4306 -154.2553" rotation="179.9362 0.4636 179.8755" 
     * valueScale="1.000000" fillLevel="4000.000000" 
     * farmId="1" age="18" 
     * rotVolume="0.000000" initVolume="4000.000000"/>
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fs_farm_bale', function (Blueprint $table) {
            $table->id();        
            $table->unsignedTinyInteger('farm_id');
            $table->unsignedBigInteger('game_id');                                      // original id from game, can change from save to save, just to match any connected device
            // end of indexes section
            $table->string('bale_type',20);                                             // square, round - needs to be calculated from the filename
            $table->string('filename',100);         
            $table->double('value_scale', 16, 6);
            $table->double('age', 16, 6)->nullable()->comment('seasons');               // available only with seasons mod
            $table->double('rot_volume', 16, 6)->nullable()->comment('seasons');        // available only with seasons mod
            $table->double('init_volume', 16, 6)->nullable()->comment('seasons');       // available only with seasons mod
            $table->boolean('is_inline');                                               // original isConnectedToInline
            $table->string('fill_type', 100)->nullable();                               // fill type needs to be calculated from the filename :/
            $table->double('fill_level', 16, 6)->nullable();
            $table->string('position', 50);
            $table->double('wrapping_state', 16, 6)->nullable()->comment('silage');     // only silage bales
            $table->string('wrapping_color', 50)->nullable()->comment('silage');        // only silage bales
            // system colulmns
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            // define indexes
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
        Schema::dropIfExists('fs_farm_bale');
    }
}
