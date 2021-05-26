<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiloProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fs_silo_product', function (Blueprint $table) {
            $table->id();              
            $table->unsignedBigInteger('silo_id');            
            // end of indexes section
            $table->string('fill_type', 50);
            $table->double('fill_level', 16, 6); 
            // system colulmns            
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            // definSe indexes
            $table->index('silo_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fs_silo_product');
    }
}
