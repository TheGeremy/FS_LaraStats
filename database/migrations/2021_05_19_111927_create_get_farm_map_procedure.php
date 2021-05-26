<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGetFarmMapProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    $query = <<<SQL
DROP PROCEDURE IF EXISTS get_farm_map;

CREATE PROCEDURE get_farm_map(save_id INT)
BEGIN  
   SELECT `id`
         ,`game_id`
     FROM `fs_farm`
    WHERE `save_id` = save_id;  
END
SQL;
        DB::connection()->getPdo()->exec($query);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $query = "DROP PROCEDURE IF EXISTS get_farm_map;";
        DB::connection()->getPdo()->exec($query);
    }
}
