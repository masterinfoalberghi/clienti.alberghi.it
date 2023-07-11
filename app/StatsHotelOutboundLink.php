<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatsHotelOutboundLink extends Model
{
  protected $table = 'tblStatsHotelOutboundLink';

	/**
	 * In questa tabella ho solo il timestamp created_at
	 * lo gestisco manualmente, perchè altrimenti laravel cercherebbe di scrivere anche su
	 * update_at facendo fallire la query
	 *
	 * @var bool
	 */
	
	public $timestamps = false;

	public function cliente() {
		return $this->belongsTo('App\Hotel', 'hotel_id', 'id');
	}

	public function scopeBeforeToday($query) {
		return $query->where("created_at", "<", date("Y-m-d 00:00:00"));
	}

}