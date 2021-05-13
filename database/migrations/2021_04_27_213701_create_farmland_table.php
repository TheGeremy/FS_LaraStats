<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmlandTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fs_farmland_dim', function (Blueprint $table) {
            $table->id();  
            $table->unsignedTinyInteger('map_id');
            $table->unsignedTinyInteger('game_field_id');
            // end of indexes section
            $table->string('npc_name', 50);
            $table->double('price_scale', 16, 6);
            $table->float('ha', 100)->nullable();
            $table->string('owning', 100)->nullable();
            $table->string('land_name', 100)->nullable();
            $table->string('note', 100)->nullable();
            // system colulmns            
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            // idnex definition
            $table->index('map_id');
            $table->index('game_field_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fs_farmland_dim');
    }
}
