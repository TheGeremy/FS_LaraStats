<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMapLandTable extends Migration
{
    /**
     * Information about fields on map from map config folder and farmlands.xml file
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fs_map_land_dim', function (Blueprint $table) {
            $table->id();  
            $table->unsignedBigInteger('map_id');           // id from fs_map_dim database table 
            $table->unsignedTinyInteger('land_id');         // id from map config farmlands.xml
            // end of indexes section
            $table->string('npc_name', 50);
            $table->double('price_scale', 16, 6);
            $table->boolean('default', 100)->nullable();
            $table->float('ha', 100)->nullable();
            $table->string('land_name', 100)->nullable();
            $table->string('note', 100)->nullable();
            // system colulmns            
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            // idnex definition
            $table->foreign('map_id')->references('id')->on('fs_map_dim')->onDelete('cascade')->onUpdate('cascade');
            $table->index('land_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fs_map_land_dim');
    }
}
