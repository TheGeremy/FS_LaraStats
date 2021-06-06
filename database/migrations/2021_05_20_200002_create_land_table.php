<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLandTable extends Migration
{
    /**
     * Information about fields and lands in game and relation to farm from savegame folder and farmland.xml file
     *
     * @return void
     */
    public function up()
    {
        $tableName = 'fs_land';

        Schema::create($tableName, function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('save_id');              
            $table->unsignedBigInteger('farm_id');            
            $table->unsignedTinyInteger('land_id');            
            // end of indexes section
            // system colulmns            
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            // define indexes
            $table->foreign('save_id')->references('id')->on('fs_savegame')->onDelete('cascade')->onUpdate('cascade');
            $table->index('farm_id');
            $table->index('land_id');
        });

        DB::statement("ALTER TABLE `$tableName` comment 'Information about fields and lands in game and relation to farm from savegame folder and farmland.xml file'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fs_land');
    }
}
