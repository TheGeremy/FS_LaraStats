<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmTreeTable extends Migration
{
    /**
     * Information about all decorative trees from savegame folder and items.xml file
     *
     * @return void
     */
    public function up()
    {
        $tableName = 'fs_farm_tree';

        Schema::create($tableName, function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('farm_id');            
            $table->unsignedInteger('game_id');
            // end of indexes section
            $table->string('class_name', 100);
            $table->string('name', 50);
            $table->string('mod_name', 100)->nullable()->comment('mods only');
            $table->string('filename', 100);
            $table->string('position', 50);
            $table->boolean('bought');
            $table->double('age', 16, 6);
            $table->double('price', 16, 6);
            $table->double('seasons_years', 16, 6)->nullable()->comment('seasons');
            // system colulmns            
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            // define indexes
            $table->foreign('farm_id')->references('id')->on('fs_farm')->onDelete('cascade')->onUpdate('cascade');
            $table->index('game_id');
        });

        DB::statement("ALTER TABLE `$tableName` comment 'Information about all decorative trees from savegame folder and items.xml file'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fs_farm_tree');
    }
}
