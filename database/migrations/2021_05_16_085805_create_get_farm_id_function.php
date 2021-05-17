<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGetFarmIdFunction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    $query = <<<SQL
DROP FUNCTION IF EXISTS get_farm_id;    
CREATE FUNCTION get_farm_id ()
RETURNS int(11)
BEGIN
  DECLARE farm_id int;

  SELECT id INTO farm_id
    FROM `fs_farm`
   WHERE `save_id` = get_save_id();

  RETURN IF(farm_id IS NULL, 0, farm_id);
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
        $query = "DROP FUNCTION IF EXISTS get_farm_id;";
        DB::connection()->getPdo()->exec($query);
    }
}
