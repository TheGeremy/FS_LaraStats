<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fs_farm_stat', function (Blueprint $table) {
            $table->id();        
            $table->unsignedBigInteger('farm_id');                
            // end of indexes section
            $table->double('revenues', 16, 6)->default(0);
            $table->double('expenses', 16, 6)->default(0);
            // bales
            $table->unsignedBigInteger('bales')->default(0);
            // distance
            $table->double('traveled_distance', 16, 6)->default(0);            
            // new animals
            $table->unsignedInteger('breed_cows')->default(0);
            $table->unsignedInteger('breed_sheeps')->default(0);
            $table->unsignedInteger('breed_pigs')->default(0);
            $table->unsignedInteger('breed_chickens')->default(0);
            $table->unsignedInteger('breed_horses')->default(0);
            // missions
            $table->unsignedInteger('field_job_mission')->default(0);
            $table->unsignedInteger('field_job_mission_npc')->default(0);
            $table->unsignedInteger('transport_mission')->default(0);
            // forestry
            $table->unsignedInteger('planted_trees')->default(0);
            $table->unsignedInteger('cut_trees')->default(0);
            $table->string('tree_types_cut', 50)->default('000000');
            $table->double('wood_tons_sold', 16, 6)->default(0);
            // usage
            $table->double('fuel_usage', 16, 6)->default(0);
            $table->double('seed_usage', 16, 6)->default(0);
            $table->double('spray_usage', 16, 6)->default(0);
            // hectares
            $table->double('worked_ha', 16, 6)->default(0);
            $table->double('cultivated_ha', 16, 6)->default(0);
            $table->double('sown_ha', 16, 6)->default(0);
            $table->double('fertilized_ha', 16, 6)->default(0);
            $table->double('threshed_ha', 16, 6)->default(0);
            $table->double('plowed_ha', 16, 6)->default(0);
            // time
            $table->double('worked_time', 16, 6)->default(0);
            $table->double('cultivated_time', 16, 6)->default(0);
            $table->double('sown_time', 16, 6)->default(0);
            $table->double('fertilized_time', 16, 6)->default(0);
            $table->double('threshed_time', 16, 6)->default(0);
            $table->double('plowed_time', 16, 6)->default(0);
            $table->double('play_time', 16, 6)->default(0);
            // system colulmns
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            // define indexes
            $table->index('farm_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fs_farm_stat');
    }
}
