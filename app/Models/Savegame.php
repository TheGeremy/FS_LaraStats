<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Savegame extends Model
{
    //use HasFactory;
    protected $table = 'fs_savegame';

    public function farms() {
    	// hasOne, hasMany, belongsTo, belongsToMany
    	return $this->hasMany(Farm::class,'save_id');
    }

    public function missions() {
    	// hasOne, hasMany, belongsTo, belongsToMany
    	return $this->hasMany(Mission::class,'save_id');
    } 
}
