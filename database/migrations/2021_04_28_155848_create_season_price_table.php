<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeasonPriceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fs_season_price', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('save_id');
            $table->unsignedTinyInteger('season');
            $table->unsignedSmallInteger('season_day');
            $table->unsignedBigInteger('fruit_type_id')->default(1);            
            // end of indexes
            //$table->string('fruit_name', 50); // original fruit_name // will be in fs_fruit_dim
            $table->double('price', 16, 6)->default(0);
            // system colulmns
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            // define indexes
            $table->index('save_id');
            $table->index('season');
            $table->index('season_day');
            $table->index('fruit_type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fs_season_price');
    }
}
