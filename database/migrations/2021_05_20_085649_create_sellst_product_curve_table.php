<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellstProductCurveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fs_sellst_product_curve', function (Blueprint $table) {
            $table->id();            
            $table->unsignedInteger('product_id');
            // end of indexes section
            $table->string('type', 50);
            $table->double('nominal_amplitude', 16, 6);
            $table->double('nominal_amplitude_variation', 16, 6);
            $table->unsignedInteger('amplitude_distribution');
            $table->double('nominal_period', 16, 6);
            $table->double('nominal_period_variation', 16, 6);
            $table->unsignedInteger('period_distribution');
            $table->double('amplitude', 16, 6);
            $table->double('period', 16, 6);
            $table->double('time', 16, 6);
            // system colulmns            
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            // define indexes
            $table->index('product_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fs_sellst_product_curve');
    }
}
