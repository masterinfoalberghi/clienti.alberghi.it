<?php namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {

	/**
	 * The Artisan commands provided by your application.
	 * 
	 * In Laravel 5.5, Artisan can automatically discover commands so that you do not have to manually register them in your kernel. 
	 * @var array
	 */
	protected $commands = [
		'App\Console\Commands\alternate',
		'App\Console\Commands\Inspire',
	    'App\Console\Commands\ImageCreateVersions',
	    'App\Console\Commands\SendEmails',
	    // 'App\Console\Commands\SendEmailsTest',
	    'App\Console\Commands\ImgSitemap',
	    'App\Console\Commands\SetOfferteFromVisibilita',
	    'App\Console\Commands\ImportStatsVetrine',
	    'App\Console\Commands\ImportStatsHotel',
	    'App\Console\Commands\ImportStatsCalls',
	    'App\Console\Commands\CheckNewPaginaStradario',
	    'App\Console\Commands\CreatePuntiDiForzaTemp',
	    'App\Console\Commands\OptimizeJPG',
	    'App\Console\Commands\StatsMailMultiplaMobile',
	    'Skmetaly\EmailVerifier\Commands\TestEmailValidator',
	    'App\Console\Commands\importOldDataSahre', 
	    'App\Console\Commands\RicalcolaDistanze', 
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */

	/*

	DEFINIRE SUL SERVER UN CRON

	* * * * * php /var/www/info-alberghi.com/master/artisan schedule:run >> /dev/null 2>&1

	*/

	protected function schedule(Schedule $schedule)
	{
				
	}


	/**
	 * Register the commands for the application.
	 *
	 * @return void
	 */
	protected function commands()
	{
	    $this->load(__DIR__.'/Commands');
	}

}
