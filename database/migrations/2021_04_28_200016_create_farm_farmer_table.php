<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmFarmerTable extends Migration
{
    /**
     * Information about players from savegame folder and farms.xml file
     *
     * @return void
     */
    public function up()
    {
        $tableName = 'fs_farm_farmer';

        Schema::create($tableName, function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('farm_id');
            // end of indexes section
            $table->string('unique_user_id', 100);
            $table->string('last_nickname', 50);
            $table->boolean('farm_manager');
            $table->boolean('buy_vehicle');
            $table->boolean('sell_vehicle');
            $table->boolean('buy_placeable');
            $table->boolean('sell_placeable');
            $table->boolean('manage_contracts');
            $table->boolean('trade_animals');
            $table->boolean('create_fields');
            $table->boolean('landscaping');
            $table->boolean('hire_assistant');
            $table->boolean('reset_vehicle');
            $table->boolean('manage_rights');
            $table->boolean('transfer_money');
            $table->boolean('update_farm');
            $table->boolean('manage_contracting');
            // system colulmns
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            // define indexes
            $table->foreign('farm_id')->references('id')->on('fs_farm')->onDelete('cascade')->onUpdate('cascade');
        });

        DB::statement("ALTER TABLE `$tableName` comment 'Information about players from savegame folder and farms.xml file'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fs_farm_farmer');
    }
}
