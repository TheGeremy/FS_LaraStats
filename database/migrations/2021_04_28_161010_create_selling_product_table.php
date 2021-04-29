<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellingProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fs_selling_product', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('save_id');
            $table->unsignedTinyInteger('farm_id'); 
            $table->unsignedSmallInteger('sell_st_id');
            $table->unsignedBigInteger('fill_id')->default(1); // id from fs_fill_type_dim
            // end of indexes
            // $table->string('fill_name', 100); // original fill_type // will be in fs_fill_type_dim
            $table->double('received', 16, 6)->default(0);
            $table->double('paid', 16, 6)->default(0);
            $table->unsignedTinyInteger('price_version');
            $table->unsignedTinyInteger('is_in_plateau');
            $table->unsignedSmallInteger('next_plateau_number');
            $table->unsignedInteger('plateau_duration')->default(51840000);
            $table->double('mean_value', 16, 6)->default(0);
            $table->double('plateau_time', 16, 6)->default(0);
            // system colulmns
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            // define indexes
            $table->index('save_id');
            $table->index('farm_id');
            $table->index('sell_st_id');
            $table->index('fill_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fs_selling_product');
    }
}
