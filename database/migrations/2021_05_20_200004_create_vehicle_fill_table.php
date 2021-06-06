<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehicleFillTable extends Migration
{
    /**
     * Information about fill types in vehicle from items.xml of savegame folder
     *
     * @return void
     */
    public function up()
    {
        $tableName = 'fs_vehicle_fill';

        Schema::create($tableName, function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vehicle_id');
            // end of indexes section
            $table->string('fill_type', 50);
            $table->double('fill_level', 16, 6);
            // system colulmns
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            // define indexes
            $table->foreign('vehicle_id')->references('id')->on('fs_vehicle')->onDelete('cascade')->onUpdate('cascade');
        });

        DB::statement("ALTER TABLE `$tableName` comment 'Information about fill types in vehicle from items.xml of savegame folder'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fs_vehicle_fill');
    }
}

