<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBgaBunkerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fs_bga_bunker', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bga_id');
            // end of indexes section            
            $table->unsignedTinyInteger('index_id');
            $table->unsignedTinyInteger('state');
            $table->double('fill_level', 16, 6);
            $table->double('compacted_level', 16, 6);
            $table->double('fermenting_time', 16, 6);
            $table->boolean('opened_front');
            $table->boolean('opened_back');            
            // system colulmns            
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            // define indexes
            $table->index('bga_id');
   
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fs_bga_bunker');
    }
}
