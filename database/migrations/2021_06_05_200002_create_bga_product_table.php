<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBgaProductTable extends Migration
{
    /**
     * Holds all product (fill types) of all bga from items.xml of savegame folder
     *
     * @return void
     */
    public function up()
    {
        $tableName = 'fs_bga_product';

        Schema::create($tableName, function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bga_id');
            // end of indexes section            
            $table->string('type', 50);
            $table->string('fill_type', 100);
            $table->double('fill_level', 16, 6);
            // system colulmns            
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            // define indexes
            $table->foreign('bga_id')->references('id')->on('fs_bga')->onDelete('cascade')->onUpdate('cascade');
        });

        DB::statement("ALTER TABLE `$tableName` comment 'Holds all product (fill types) of all bga from items.xml of savegame folder'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fs_bga_product');
    }
}
