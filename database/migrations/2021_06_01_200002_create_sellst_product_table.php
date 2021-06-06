<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellstProductTable extends Migration
{
    /**
     * Holds all selling stations products from items.xml of savegame folder
     *
     * @return void
     */
    public function up()
    {
        $tableName = 'fs_sellst_product';

        Schema::create($tableName, function (Blueprint $table) {
            $table->id();            
            $table->unsignedBigInteger('sell_st_id');
            // end of indexes section
            $table->string('fill_type', 50);
            $table->double('received', 16, 6);
            $table->double('paid', 16, 6);
            $table->unsignedTinyInteger('price_version');
            $table->boolean('plateau');
            $table->unsignedInteger('next_plateau_number');
            $table->unsignedInteger('plateau_duration');
            $table->double('mean_value', 16, 6);
            $table->double('plateau_time', 16, 6);
            // system colulmns            
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            // define indexes
            $table->foreign('sell_st_id')->references('id')->on('fs_sellst')->onDelete('cascade')->onUpdate('cascade');
        });

        DB::statement("ALTER TABLE `$tableName` comment 'Holds all selling stations products from items.xml of savegame folder'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fs_sellst_product');
    }
}
