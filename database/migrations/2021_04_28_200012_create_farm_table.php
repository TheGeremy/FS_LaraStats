<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmTable extends Migration
{
    /**
     * Information about farms from savegame folder and farms.xml file
     *
     * @return void
     */
    public function up()
    {
        $tableName = 'fs_farm';

        Schema::create($tableName, function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('save_id');
            $table->unsignedTinyInteger('game_id');
            // end of indexes section                       
            $table->string('name', 100);
            $table->unsignedTinyInteger('color');
            $table->double('loan', 16, 6);            
            $table->double('money', 16, 6);
            $table->double('loan_annual_rate', 16, 6);                    
            // system colulmns
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            // define indexes
            $table->foreign('save_id')->references('id')->on('fs_savegame')->onDelete('cascade')->onUpdate('cascade');
            $table->index('game_id');
        });

        DB::statement("ALTER TABLE `$tableName` comment 'Information about farms from savegame folder and farms.xml file'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fs_farm');
    }
}
