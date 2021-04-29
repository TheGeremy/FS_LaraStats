<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fs_farm_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('save_id');
            $table->unsignedTinyInteger('farm_id');
            // end of indexes
            $table->unsignedTinyInteger('color');
            $table->double('loan', 16, 6);            
            $table->double('money', 16, 6);
            $table->double('loan_annual_rate', 16, 6);            
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
        Schema::dropIfExists('fs_farm_detail');
    }
}