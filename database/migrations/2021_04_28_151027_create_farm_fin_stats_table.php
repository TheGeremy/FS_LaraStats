<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmFinStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fs_farm_fin_stats', function (Blueprint $table) {
            $table->id();        
            $table->unsignedBigInteger('save_id');
            $table->unsignedTinyInteger('farm_id');                
            // end of indexes section
            $table->unsignedTinyInteger('stats_day');
            // incomes
            $table->double('sold_buildings', 16, 6)->default(0);
            $table->double('field_selling', 16, 6)->default(0);
            $table->double('property_income', 16, 6)->default(0);
            $table->double('sold_animals', 16, 6)->default(0);
            $table->double('sold_wood', 16, 6)->default(0);
            $table->double('sold_bales', 16, 6)->default(0);
            $table->double('sold_wool', 16, 6)->default(0);
            $table->double('sold_wilk', 16, 6)->default(0);
            $table->double('sold_vehicles', 16, 6)->default(0);            
            $table->double('harvest_income', 16, 6)->default(0);
            $table->double('income_bga', 16, 6)->default(0);
            $table->double('mission_income', 16, 6)->default(0);            
            // costs
            $table->double('field_purchase', 16, 6)->default(0);
            $table->double('new_vehicles_cost', 16, 6)->default(0);
            $table->double('new_animals_cost', 16, 6)->default(0);
            $table->double('construction_cost', 16, 6)->default(0);            
            $table->double('vehicle_running_cost', 16, 6)->default(0);
            $table->double('vehicle_leasing_cost', 16, 6)->default(0);
            $table->double('animal_upkeep', 16, 6)->default(0);
            $table->double('property_maintenance', 16, 6)->default(0);
            $table->double('purchase_fuel', 16, 6)->default(0);
            $table->double('purchase_seeds', 16, 6)->default(0);
            $table->double('purchase_fertilizer', 16, 6)->default(0);
            $table->double('purchase_saplings', 16, 6)->default(0);
            $table->double('purchase_water', 16, 6)->default(0);        
            $table->double('wage_payment', 16, 6)->default(0);
            $table->double('other', 16, 6)->default(0);
            $table->double('loan_interest', 16, 6)->default(0);
            $table->double('seasons_livery_stable', 16, 6)->default(0);
            // system colulmns
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            // define indexes
            $table->index('save_id');
            $table->index('farm_id');
            $table->index('stats_day');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fs_farm_fin_stats');
    }
}
