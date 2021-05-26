<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBsiloTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fs_bsilo', function (Blueprint $table) {
            $table->id();              
            $table->unsignedBigInteger('save_id');                                              // needed to map husbandries that dont belogs to farm
            $table->unsignedBigInteger('farm_id');            
            $table->unsignedInteger('game_id');
            // end of indexes section
            $table->string('class_name', 100);
            $table->string('name', 50)->nullable();
            $table->string('mod_name', 100)->nullable()->comment('mods only');
            $table->string('filename', 100);
            $table->string('position', 100);
            $table->double('age', 16, 6);
            $table->double('price', 16, 6);
            $table->boolean('bought');
            $table->double('seasons_years', 16, 6)->nullable();
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
        Schema::dropIfExists('fs_bsilo');
    }
}
