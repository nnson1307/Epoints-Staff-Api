<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InsertTimeOffDaysTotal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'time_off_days_total:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tạo database cho bảng time_off_days_total';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            DB::beginTransaction();
          
            $staffs = DB::table('staffs')->get();
            $timeOffType = DB::table('time_off_type')->get();

            foreach($staffs as $itemStaffs){
                foreach($timeOffType as $itemtimeOffType){
                
                    if($itemtimeOffType->time_off_type_code == '001' 
                    || $itemtimeOffType->time_off_type_code == '018'
                    || $itemtimeOffType->time_off_type_code == '017' )
                    {
                        $timeOffDaysNumber = 0;
                    }
                    DB::table('time_off_days_total')->insert([
                        'time_off_type_id' => $itemtimeOffType->time_off_type_id,
                        'staff_id' => $itemStaffs->staff_id,
                        'time_off_days_number' => $timeOffDaysNumber ?? 12
                    ]);
            
                    DB::commit();
                
                    //send output to the console
                    $this->info('Success! ');

                }
            }        

        } catch (\Exception $e) {
            DB::rollBack();
            
            $this->error($e->getMessage());
        }
    }
}
