<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGetHusbandryMapProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    $query = <<<SQL
DROP PROCEDURE IF EXISTS get_husbandry_map;

CREATE PROCEDURE get_husbandry_map(save_id INT)
BEGIN  
    SELECT h.`id`
          ,h.`game_id`
      FROM `fs_husbandry` as h
     WHERE h.`save_id` = save_id;
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
        $query = "DROP PROCEDURE IF EXISTS get_husbandry_map;";
        DB::connection()->getPdo()->exec($query);
    }
}