<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSavegameAttachTable extends Migration
{
    /**
     * Information about how vehicles are connected from savegame folder and vehicles.xml
     *
     * @return void
     */
    public function up()
    {
        $tableName = 'fs_savegame_attach';

        Schema::create($tableName, function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('save_id');
            // end of indexes section
            $table->unsignedInteger('root_id');
            $table->unsignedInteger('attach_id');
            $table->unsignedTinyInteger('joint_index_input');
            $table->unsignedTinyInteger('joint_index');
            $table->boolean('move_down');
            // system colulmns
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();        
            // define indexes            
            $table->foreign('save_id')->references('id')->on('fs_savegame')->onDelete('cascade')->onUpdate('cascade');
        });

        DB::statement("ALTER TABLE `$tableName` comment 'Information about how vehicles are connected from savegame folder and vehicles.xml'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fs_savegame_attach');
    }
}
