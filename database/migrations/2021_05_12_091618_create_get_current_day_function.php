<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGetCurrentDayFunction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    $query = <<<SQL
DROP FUNCTION IF EXISTS get_current_day ;    
CREATE FUNCTION get_current_day ()
RETURNS int(11)
BEGIN
  DECLARE current_day int;

  SELECT MAX(current_day) INTO current_day
    FROM fs_savegame
   WHERE id = get_save_id();

  RETURN IF(current_day IS NULL, 0, current_day);
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
        $query = "DROP FUNCTION IF EXISTS get_current_day;";
        DB::connection()->getPdo()->exec($query);
    }
}
