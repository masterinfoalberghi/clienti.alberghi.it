<?php

/**
 *
 * View composer per render listino MinMax (prezzi min e max):
 * @parameters: cliente, titolo
 *
 *
 *
 */

namespace App\Http\ViewComposers;

use DB;
use Lang;
use Illuminate\Contracts\View\View;
use Carbon\Carbon;
use App\Utility;
use App\Localita;
use Illuminate\Support\Facades\Cache;

class FooterComposer
{
	
	public function compose(View $view)
	{
		
	}
	
}