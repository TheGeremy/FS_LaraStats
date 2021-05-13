<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTranslationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fs_translation_dim', function (Blueprint $table) {
            $table->id();
            $table->integer('type')->comment('1/2/3   game/map/mod');       // translation type (1 - translation for base game part, 2 - translation for map mods, 3 - translation for other mods)
            $table->string('lang', 5);                                      // language to translate to
            $table->string('text_from', 50);                                // original name
            // end of indexes
            $table->string('text_to', 50);                                  // translation
            // system colulmns
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            // define indexes
            $table->index('type');
            $table->index('lang');
            $table->index('text_from');            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fs_translation_dim');
    }
}
