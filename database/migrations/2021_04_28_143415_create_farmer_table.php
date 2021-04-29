<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // table fs_farmer to store users from Farming Simulator Game
        Schema::create('fs_farmer', function (Blueprint $table) {
            $table->id();        
            $table->unsignedBigInteger('save_id');
            $table->unsignedTinyInteger('farm_id');             
            // end of indexes section
            $table->string('fs_user_role',50); // original uniqueUserId
            $table->string('last_nickname',100);
            $table->boolean('farm_manager');            
            $table->boolean('helper_manure_source');
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
            $table->boolean('transfer_mMoney');
            $table->boolean('update_farm');
            $table->boolean('manage_contracting');
            // system colulmns
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            // define indexes
            $table->index('save_id');
            $table->index('farm_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fs_farmer');
    }
}
