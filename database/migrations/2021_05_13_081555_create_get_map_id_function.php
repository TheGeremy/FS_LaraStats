<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGetMapIdFunction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    $query = <<<SQL
DROP FUNCTION IF EXISTS get_map_id;    
CREATE FUNCTION get_map_id (title TINYTEXT)
RETURNS int(11)
BEGIN
  DECLARE map_id int;

  SELECT id INTO map_id
    FROM fs_map_dim
   WHERE map_title = title;

  RETURN IF(map_id IS NULL, 0, map_id);
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
        $query = "DROP FUNCTION IF EXISTS get_map_id;";
        DB::connection()->getPdo()->exec($query);
    }
}
