<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiloProductTable extends Migration
{
    /**
     *  Holds all fill types of silos from items.xml of savegame folder
     *
     * @return void
     */
    public function up()
    {
        $tableName = 'fs_silo_product';

        Schema::create($tableName, function (Blueprint $table) {
            $table->id();              
            $table->unsignedBigInteger('silo_id');            
            // end of indexes section
            $table->string('fill_type', 50);
            $table->double('fill_level', 16, 6); 
            // system colulmns            
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            // definSe indexes
            $table->foreign('silo_id')->references('id')->on('fs_silo')->onDelete('cascade')->onUpdate('cascade');
        });

        DB::statement("ALTER TABLE `$tableName` comment 'Holds all fill types of silos from items.xml of savegame folder'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fs_silo_product');
    }
}
