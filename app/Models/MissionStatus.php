<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MissionStatus extends Model
{
    //use HasFactory;
    protected $table = 'fs_mission_status';

	public function savegame() {
    	return $this->hasMany(Mission::class,'status_id');
    }

}
