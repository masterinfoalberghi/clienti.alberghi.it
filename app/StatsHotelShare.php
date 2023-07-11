<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatsHotelShare extends Model
{
    protected $table = 'tblStatsHotelShare';
    protected $guarded = ['id'];
    protected $fillable = ['uri', 'useragent', 'IP'];
	
	public function scopeBeforeToday($query) {
		return $query->where("created_at", "<", date("Y-m-d 00:00:00"));
	}
	
}
