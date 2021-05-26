<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHusbandryAnimalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fs_husbandry_animal', function (Blueprint $table) {
            $table->id();        
            $table->unsignedBigInteger('husbandry_id');
            // end of indexes section
            $table->string('type', 50);
            $table->string('fill_type', 100);
            $table->double('dirt_scale', 16, 6);
            // seasons only
            $table->boolean('seasons_is_female')->nullable()->comment('seasons');
            $table->double('seasons_weight', 16, 6)->nullable()->comment('seasons');
            $table->double('seasons_age', 16, 6)->nullable()->comment('seasons');
            $table->unsignedInteger('seasons_id')->nullable()->comment('seasons');
            $table->double('seasons_time_to_birth', 16, 6)->nullable()->comment('seasons');
            $table->double('seasons_time_from_birth', 16, 6)->nullable()->comment('seasons');
            // horse only
            $table->boolean('riding_active')->nullable()->comment('horse');
            $table->string('name', 100)->nullable()->comment('horse');
            $table->double('fitness_scale', 16, 6)->nullable()->comment('horse');
            $table->double('health_scale', 16, 6)->nullable()->comment('horse');
            $table->double('riding_timer', 16, 6)->nullable()->comment('horse');
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
        Schema::dropIfExists('fs_husbandry_animal');
    }
}
