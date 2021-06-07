<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMissionStatusTable extends Migration
{
    /**
     * Dimension table for mission status. Translate mission status_id to mission status text.
     * You need to fill this table with Laravel seeder.
     * This table basically change the xml mission status from number to some reasonable text.
     * 1 - free
     * 2 - connected
     * 3 - tbd
     *
     * @return void
     */
    public function up()
    {
        $tableName = 'fs_mission_status';

        Schema::create($tableName, function (Blueprint $table) {
            $table->smallIncrements('id');
            // end of indexes
            $table->string('name', 50)->unique();
            // system colulmns
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            // define indexes
        });

        DB::statement("ALTER TABLE `$tableName` comment 'Dimension table for mission status. Translate mission status_id to mission status text.'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fs_mission_status');
    }
}
