<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHusbandryAnimalTable extends Migration
{
    /**
     * Animals of each husbandry from items.xml of savegame folder
     *
     * @return void
     */
    public function up()
    {
        $tableName = 'fs_husbandry_animal';

        Schema::create($tableName, function (Blueprint $table) {
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
            $table->foreign('husbandry_id')->references('id')->on('fs_husbandry')->onDelete('cascade')->onUpdate('cascade');
        });

        DB::statement("ALTER TABLE `$tableName` comment 'Animals of each husbandry from items.xml of savegame folder'");
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
