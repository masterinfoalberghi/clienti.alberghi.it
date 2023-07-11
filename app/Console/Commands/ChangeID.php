<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ChangeID extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hotel:change_id  
                            {old_id : Vecchio id da sostituire} 
                            {new_id : Nuovo Id }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    private static function change ($table, $column, $old_id, $new_id) {

        //dd($table, $column, $old_id, $new_id);
        

    }
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        
        $old_id = $this->argument('old_id');
        $new_id = $this->argument('new_id');
        $tables = DB::select('SHOW TABLES'); //
        
        foreach($tables as $table):

            $columns = DB::select('SHOW COLUMNS FROM '.$table->Tables_in_infoalberghi.';');

            foreach($columns as $column):
                if ($column->Field == "hotel_id" || $column->Field == "id_hotel") {
                    echo $table->Tables_in_infoalberghi . "\n";
                    DB::table("" . $table->Tables_in_infoalberghi)->where("" . $column->Field, $old_id)->update([$column->Field => $new_id]);
                }
            endforeach;

            DB::table("tblHotel")->where("id", $old_id)->update(["id" => $new_id]);
            
        endforeach;
        return 0;
    }
}
