<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckGcompFunction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    $query = <<<SQL
DROP FUNCTION IF EXISTS check_gcomp;   
CREATE FUNCTION check_gcomp ()
RETURNS boolean
BEGIN
  DECLARE mod_id int;

  SELECT id into mod_id
    FROM fs_savegame_mod
   WHERE save_id = get_save_id()
     AND LOWER(title) = 'globalcompany';
 
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
        $query = "DROP FUNCTION IF EXISTS check_gcomp;";
        DB::connection()->getPdo()->exec($query);
    }
}
