<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSavegameTrainFillTable extends Migration
{
    /**
     * Information about fill types in train from savegame folder and vehicles.xml file
     *
     * @return void
     */
    public function up()
    {
        $tableName = 'fs_savegame_train_fill';

        Schema::create($tableName, function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('train_id');
            // end of indexes section
            $table->string('fill_type', 50);
            $table->double('fill_level', 16, 6);
            // system colulmns
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            // define indexes
            $table->foreign('train_id')->references('id')->on('fs_savegame_train')->onUpdate('cascade')->onDelete('cascade');
        });

        DB::statement("ALTER TABLE `$tableName` comment 'Information about fill types in train from savegame folder and vehicles.xml file'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fs_savegame_train_fill');
    }
}
