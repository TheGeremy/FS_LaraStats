<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGetSaveIdFunction extends Migration
{
    /**
     * Declare function that find id of last savegame stored in database
     *
     * @return void
     */
    public function up()
    {
    $query = <<<SQL
DROP FUNCTION IF EXISTS get_save_id ;
CREATE FUNCTION get_save_id ()
RETURNS int(11)
BEGIN
DECLARE save_id int;

SELECT
MAX(fs.`id`) INTO save_id
FROM fs_savegame fs;
RETURN IF(save_id IS NULL, 0, save_id);
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
        $query = "DROP FUNCTION IF EXISTS get_save_id;";
        DB::connection()->getPdo()->exec($query);
    }
}
