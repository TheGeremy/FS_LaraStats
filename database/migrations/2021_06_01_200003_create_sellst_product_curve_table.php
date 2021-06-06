<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellstProductCurveTable extends Migration
{
    /**
     * Holds all selling stations products curves from items.xml of savegame folder
     *
     * @return void
     */
    public function up()
    {
        $tableName = 'fs_sellst_product_curve';

        Schema::create($tableName, function (Blueprint $table) {
            $table->id();            
            $table->unsignedBigInteger('product_id');
            // end of indexes section
            $table->string('type', 50);
            $table->double('nominal_amplitude', 16, 6);
            $table->double('nominal_amplitude_variation', 16, 6);
            $table->unsignedInteger('amplitude_distribution');
            $table->double('nominal_period', 16, 6);
            $table->double('nominal_period_variation', 16, 6);
            $table->unsignedInteger('period_distribution');
            $table->double('amplitude', 16, 6);
            $table->double('period', 16, 6);
            $table->double('time', 16, 6);
            // system colulmns            
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            // define indexes
            $table->foreign('product_id')->references('id')->on('fs_sellst_product')->onDelete('cascade')->onUpdate('cascade');
        });

        DB::statement("ALTER TABLE `$tableName` comment 'Holds all selling stations products curves from items.xml of savegame folder'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fs_sellst_product_curve');
    }
}
