<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmlandTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fs_farmland_dim', function (Blueprint $table) {
            $table->id();         
            // end of indexes section
            $table->string('npcName', 50);
            $table->double('priceScale', 16, 6);
            $table->string('land_name', 100)->nullable();
            $table->string('note', 100)->nullable();
            // system colulmns            
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fs_farmland_dim');
    }
}
