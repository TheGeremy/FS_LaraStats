<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMapDetailTable extends Migration
{
    /**
     * Information about mod map settings from savegame folder and gameSavegame.xml, can change from savegame to savegame
     * Values here can change from savegame to savegame
     *
     * @return void
     */
    public function up()
    {
        $tableName = 'fs_map_detail';

        Schema::create($tableName, function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('save_id');
            $table->unsignedBigInteger('map_id'); // id from fs_map_dim
            // end of indexes section
            $table->unsignedTinyInteger('density_map_revision');
            $table->unsignedTinyInteger('terrain_lod_texture_revision');
            $table->unsignedTinyInteger('split_shapes_revision');
            $table->unsignedTinyInteger('tip_collision_revision');
            $table->unsignedTinyInteger('placement_collision_revision');
            $table->unsignedTinyInteger('map_density_map_revision');
            $table->unsignedTinyInteger('map_terrain_lod_texture_revision');
            $table->unsignedTinyInteger('map_split_shapes_revision');
            $table->unsignedTinyInteger('map_tip_collision_revision');
            $table->unsignedTinyInteger('map_placement_collision_revision');            
            // system colulmns
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            // define indexes
            $table->index('save_id');
            $table->foreign('map_id')->references('id')->on('fs_map_dim')->onDelete('cascade')->onUpdate('cascade');
        });

        DB::statement("ALTER TABLE `$tableName` comment 'Information about mod map settings from savegame folder and gameSavegame.xml, can change from savegame to savegame'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fs_map_detail');
    }
}
