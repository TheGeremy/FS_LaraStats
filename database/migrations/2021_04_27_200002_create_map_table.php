<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMapTable extends Migration
{
    /**
     * Table to store information about mod map from map config folder and modDesc.xml
     * Talbes with suffix _dim are independent from savegame table, values dont change from savegame to savegame
     *
     * @return void
     */
    public function up()
    {
        $tableName = 'fs_map_dim';

        Schema::create($tableName, function (Blueprint $table) {
            $table->id();
            // end of indexes section           
            $table->string('title', 100); // from map mod modDesc.xml
            $table->string('note', 250)->nullable(); // note from user on backend
            // to fill those below, you need to extract from map mod zip file modDesc.xml to fs_config/map_config/map_name_of_map
            $table->string('author', 100)->nullable(); // map author from mod zip modDesc.xml file
            $table->string('version', 10)->nullable(); // map version from mod zip modDesc.xml >> 1.2.0.0
            $table->string('desc_version', 10)->nullable(); // map xml description version from mod zip modDesc.xml >> descVersion="46"
            $table->string('short_desc_en', 250)->nullable();  // map mod zip >> modDesc.xml >>  <maps><map><description><en>
            $table->string('short_desc_cz', 250)->nullable();  // map mod zip >> modDesc.xml >>  <maps><map><description><cz>
            $table->text('description_en')->nullable(); // map author from mod zip modDesc.xml file
            $table->text('description_cz')->nullable(); // map author from mod zip modDesc.xml file
            // system colulmns
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });

        DB::statement("ALTER TABLE `$tableName` comment 'Table to store information about mod map from map config folder and modDesc.xml'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fs_map_dim');
    }
}
