<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckSeasonsFunction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    $query = <<<SQL
DROP FUNCTION IF EXISTS check_seasons;   
CREATE FUNCTION check_seasons ()
RETURNS boolean
BEGIN
  DECLARE mod_id int;

  SELECT id into mod_id
    FROM fs_mod
   WHERE save_id = get_save_id()
     AND LOWER(title) = 'seasons';
 
  RETURN IF(mod_id IS NULL, FALSE, TRUE);
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
        $query = "DROP FUNCTION IF EXISTS check_seasons;";
        DB::connection()->getPdo()->exec($query);
    }
}
