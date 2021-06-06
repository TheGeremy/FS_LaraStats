<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGlobalCompTable extends Migration
{
    /**
     * Holds global company mod settings from globalCompany.xml of savegame folder
     * Filled only when globalCompany mod is active in savegame
     *
     * @return void
     */
    public function up()
    {
        $tableName = 'fs_global_comp';

        Schema::create($tableName, function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('save_id');
            // end of indexes section
            $table->string('setting', 100);
            $table->boolean('value');
            // system colulmns            
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            // define indexes
            $table->foreign('save_id')->references('id')->on('fs_savegame')->onDelete('cascade')->onUpdate('cascade');
        });

        DB::statement("ALTER TABLE `$tableName` comment 'Holds global company mod settings from globalCompany.xml of savegame folder'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fs_global_comp');
    }
}
