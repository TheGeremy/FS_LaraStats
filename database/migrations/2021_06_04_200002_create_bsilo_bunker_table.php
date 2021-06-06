<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBsiloBunkerTable extends Migration
{
    /**
     * Holds all bunkers of all silos from items.xml of savegame folder
     *
     * @return void
     */
    public function up()
    {
        $tableName = 'fs_bsilo_bunker';

        Schema::create($tableName, function (Blueprint $table) {
            $table->id();              
            $table->unsignedBigInteger('bsilo_id');
            // end of indexes section
            $table->unsignedTinyInteger('index_id');
            $table->unsignedTinyInteger('state');
            $table->double('fill_level', 16, 6);
            $table->double('compacted_level', 16, 6);
            $table->double('fermenting_time', 16, 6);
            $table->boolean('opened_front');
            $table->boolean('opened_back');            
            // system colulmns            
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            // define indexes
            $table->foreign('bsilo_id')->references('id')->on('fs_bsilo')->onDelete('cascade')->onUpdate('cascade');
        });

        DB::statement("ALTER TABLE `$tableName` comment 'Holds all bunkers of all silos from items.xml of savegame folder'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fs_bsilo_bunker');
    }
}
