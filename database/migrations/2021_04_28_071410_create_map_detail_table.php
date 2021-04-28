<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMapDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fs_map_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('save_id');
            $table->unsignedBigInteger('map_id');
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
            $table->index('map_id');
        });
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
