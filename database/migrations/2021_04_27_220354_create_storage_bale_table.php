<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStorageBaleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fs_storage_bale', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('save_id');
            $table->unsignedTinyInteger('farm_id'); 
            // end of indexes
            $table->string('class_name', 100);
            $table->unsignedSmallInteger('source_id'); 
            $table->string('mod_name', 100)->nullable();
            $table->boolean('is_connected_to_inline');
            $table->string('filename', 100);
            $table->integer('fill_Level');             
            $table->double('age', 16, 6);
            $table->double('rot_volume', 16, 6);
            $table->double('init_volume', 16, 6);
            // system colulmns
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            // define indexes
            $table->index('save_id');
            $table->index('farm_id');            
            //$table->foreign('save_id')->references('id')->on('fs_savegames');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fs_storage_bale');
    }
}
