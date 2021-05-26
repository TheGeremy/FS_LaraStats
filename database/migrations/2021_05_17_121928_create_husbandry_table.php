<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHusbandryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fs_husbandry', function (Blueprint $table) {
            $table->id();              
            $table->unsignedBigInteger('save_id');                                              // needed to map husbandries that dont belogs to farm
            $table->unsignedBigInteger('farm_id');            
            $table->unsignedInteger('game_id');
            // end of indexes section
            $table->string('mod_name', 100)->nullable()->comment('mods only');                  // vanilla objects dont have modName attribute
            $table->string('class_name',100);
            $table->string('filename',100);
            $table->string('animal',50);
            $table->string('position',50);            
            $table->double('price', 16, 6);
            $table->boolean('default');
            $table->double('age', 16, 6);
            $table->double('seasons_years', 16, 6)->nullable()->comment('seasons');             // only sesasons mod
            $table->double('glb_prod_factor', 16, 6)->nullable();                               // only global company mod I assume
            $table->double('avg_glb_prod_factor', 16, 6)->nullable();                           // only global company mod I assume
            // system colulmns            
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            // define indexes
            $table->index('save_id');
            $table->index('farm_id');
            $table->index('game_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fs_husbandry');
    }
}
