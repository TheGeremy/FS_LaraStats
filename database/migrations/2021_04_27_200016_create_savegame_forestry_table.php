<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSavegameForestryTable extends Migration
{
    /**
     * Information about forestry trees of savegame and relation to farm from savegame folder and treePlant.xml file
     *
     * @return void
     */
    public function up()
    {
        $tableName = 'fs_savegame_forestry';

        Schema::create($tableName, function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('save_id');
            // end of indexes
            $table->string('type', 50);
            $table->string('position', 100);
            $table->double('growth_state', 16, 6);
            $table->unsignedTinyInteger('growth_state_i');
            $table->boolean('is_growing');
            // system colulmns
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            // define indexes
            $table->foreign('save_id')->references('id')->on('fs_savegame')->onDelete('cascade')->onUpdate('cascade');
        });

        DB::statement("ALTER TABLE `$tableName` comment 'Information about forestry trees of savegame and relation to farm from savegame folder and treePlant.xml file'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fs_savegame_forestry');
    }
}
