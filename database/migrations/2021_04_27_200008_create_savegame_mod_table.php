<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSavegameModTable extends Migration
{
    /**
     * Information about active mods in game from savegame folder and mods.xml
     *
     * @return void
     */
    public function up()
    {
        $tableName = 'fs_savegame_mod';

        Schema::create($tableName, function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('save_id');
            // end of indexes
            $table->string('mod_name', 100);
            $table->string('title', 250);
            $table->string('version', 50)->default('1.0.0.0');
            $table->boolean('required');            
            $table->string('file_hash', 50);
            // system colulmns
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            // define indexes
            $table->foreign('save_id')->references('id')->on('fs_savegame')->onUpdate('cascade')->onDelete('cascade');
        });

        DB::statement("ALTER TABLE `$tableName` comment 'Information about active mods in game from savegame folder and mods.xml'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fs_savegame_mod');
    }
}
