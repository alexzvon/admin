<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Services\Shop\QuadCRMTimesService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class QuadTimesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('quad_c_r_m_times')->insert([
            [
                'admin_id' => null,
                'time' => '00:00:00',
                'status' => QuadCRMTimesService::TIME_FOR_EXPORT,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'admin_id' => null,
                'time' => '00:00:00',
                'status' => QuadCRMTimesService::TIME_FOR_IMPORT,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
