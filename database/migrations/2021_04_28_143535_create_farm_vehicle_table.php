<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmVehicleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fs_farm_vehicle', function (Blueprint $table) {
            $table->id();        
            $table->unsignedBigInteger('save_id');
            $table->unsignedTinyInteger('farm_id');                
            // end of indexes section
            $table->string('mod_name',100);
            $table->string('filename',100);
            $table->unsignedTinyInteger('propertyState')->default(0);            
            $table->double('age', 16, 6);
            $table->double('price', 16, 6);
            $table->double('operatingTime', 16, 6);
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
        Schema::dropIfExists('fs_farm_vehicle');
    }
}
