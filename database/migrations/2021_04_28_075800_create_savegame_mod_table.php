<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSavegameModTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fs_savegame_mod', function (Blueprint $table) {
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
            $table->index('save_id');  
        });
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
