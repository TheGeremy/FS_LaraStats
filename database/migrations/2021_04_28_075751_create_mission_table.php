<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fs_mission', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('save_id');            
            $table->unsignedTinyInteger('field_id');
            $table->unsignedTinyInteger('fruit_type_id');                             
            $table->unsignedTinyInteger('farm_id');            
            // end of indexes   
            $table->unsignedTinyInteger('mission_num');
            $table->string('savegame_name', 100);
            $table->integer('reward');
            $table->unsignedTinyInteger('status');
            $table->unsignedTinyInteger('success');
            $table->double('vehicle_use_cost', 16, 6);
            $table->string('fruit_type_name', 100);
            // system colulmns
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            // define indexes
            $table->index('save_id');
            $table->index('field_id');
            $table->index('fruit_type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fs_mission');
    }
}
