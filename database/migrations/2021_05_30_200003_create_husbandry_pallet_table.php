<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHusbandryPalletTable extends Migration
{
    /**
     * Inrormation about pallet module of husbandry from items.xml of savegame folder
     *
     * @return void
     */
    public function up()
    {
        $tableName = 'fs_husbandry_pallet';

        Schema::create($tableName, function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('husbandry_id');
            // end of indexes section
            $table->double('fill_capacity', 16, 6);
            $table->double('fill_delta', 16, 6);
            // system colulmns
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            // define indexes
            $table->foreign('husbandry_id')->references('id')->on('fs_husbandry')->onDelete('cascade')->onUpdate('cascade');
        });

        DB::statement("ALTER TABLE `$tableName` comment 'Inrormation about pallet module of husbandry from items.xml of savegame folder'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fs_husbandry_pallet');
    }
}
