<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mission extends Model
{
    //use HasFactory;
	protected $table = 'fs_savegame_mission';

    public function savegame() {
    	// hasOne, hasMany, belongsTo, belongsToMany
    	return $this->belongsTo(Savegame::class,'save_id');
    }

    public function status() {
    	// hasOne, hasMany, belongsTo, belongsToMany
    	return $this->belongsTo(MissionStatus::class,'status_id');
    }    
}
