<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMapFarmlandDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fs_map_farmland_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('save_id');              
            $table->unsignedTinyInteger('farm_id');            
            $table->unsignedTinyInteger('game_field_id');            
            // end of indexes section
            $table->string('land_name', 100)->nullable();
            // system colulmns            
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            // define indexes
            $table->index('save_id');
            $table->index('farm_id');
            $table->index('game_field_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fs_map_farmland_detail');
    }
}
