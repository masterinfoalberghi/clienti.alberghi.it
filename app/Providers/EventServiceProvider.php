<?php
namespace App\Providers;

use Illuminate\Events\Dispatcher;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider {

	/**
	 * The event handler mappings for the application.
	 *
	 * @var array
	 */
	protected $listen = [
		'App\Events\HotelViewHandler' => [
			'App\Listeners\LogHotelView',
		],
		
		'App\Events\VetrinaClickHandler' => [
			'App\Listeners\LogVetrinaClick',
		],

		'App\Events\VotClickHandler' => [
			'App\Listeners\LogVotClick',
		],

		'App\Events\VaatClickHandler' => [
			'App\Listeners\LogVaatClick',
		],

		'App\Events\VttClickHandler' => [
			'App\Listeners\LogVttClick',
		],

		'App\Events\VstClickHandler' => [
			'App\Listeners\LogVstClick',
		],

		/*auth.login: Some of the core events fired by Laravel now use event objects instead of string event names and dynamic parameters*/
    'Illuminate\Contracts\Auth\Authenticatable' => [
    	'App\Listeners\AdminAuthLoginEventHandler',
    ],
	];

	/**
	 * Register any other events for your application.
	 *
	 * @param  \Illuminate\Contracts\Events\Dispatcher  $events
	 * @return void
	 */
	public function boot()
	{
		parent::boot();

		//
	}

}
