<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHusbandryLmanureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fs_husbandry_lmanure', function (Blueprint $table) {
            $table->unsignedBigInteger('husbandry_id');
            // end of indexes section
            $table->string('fill_type', 50);
            $table->double('fill_capacity', 16, 6);            
            $table->double('fill_level', 16, 6);
            // system colulmns
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            // define indexes
            $table->index('husbandry_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fs_husbandry_lmanure');
    }
}
