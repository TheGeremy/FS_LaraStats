<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeasonsPriceTable extends Migration
{
    /**
     * Holds information about season prices forecast from seasons.xml of savegame folder
     * Filled only when seasons mod is active in savegame
     *
     * @return void
     */
    public function up()
    {
        $tableName = 'fs_seasons_price';

        Schema::create($tableName, function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seasons_id');
            // end of indexes section
            $table->string('fill_type', 50);
            $table->unsignedTinyInteger('day');
            $table->float('price', 16, 6);
            // system colulmns            
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            // define indexes
            $table->foreign('seasons_id')->references('id')->on('fs_seasons')->onDelete('cascade')->onUpdate('cascade');            
        });

        DB::statement("ALTER TABLE `$tableName` comment 'Holds information about season prices forecast from seasons.xml of savegame folder'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fs_seasons_price');
    }
}
