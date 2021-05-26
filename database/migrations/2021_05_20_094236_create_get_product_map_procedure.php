<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGetProductMapProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    $query = <<<SQL
DROP PROCEDURE IF EXISTS get_product_map;

CREATE PROCEDURE get_product_map(save_id INT)
BEGIN  
    SELECT p.`id`
          ,ss.`game_id` AS sell_st_game_id
          ,p.`fill_type`
      FROM fs_sellst_product AS p
           INNER JOIN fs_sellst AS ss ON ss.`id` = p.`sell_st_id`
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
        $query = "DROP PROCEDURE IF EXISTS get_product_map;";
        DB::connection()->getPdo()->exec($query);
    }
}
