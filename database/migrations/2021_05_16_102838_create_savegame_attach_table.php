<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSavegameAttachTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fs_savegame_attach', function (Blueprint $table) {
            $table->unsignedBigInteger('save_id');
            // end of indexes section
            $table->unsignedInteger('root_id');
            $table->unsignedInteger('attach_id');
            $table->unsignedTinyInteger('joint_index_input');
            $table->unsignedTinyInteger('joint_index');
            $table->boolean('move_down');
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
        Schema::dropIfExists('fs_savegame_attach');
    }
}
