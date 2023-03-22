<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class FranchiseGmtSeeder extends Seeder
{
    private $data = [
        ['id' => 1, 'name' => 'GMT', 'hour' => 0],
        ['id' => 2, 'name' => 'GMT+1', 'hour' => 1],
        ['id' => 3, 'name' => 'GMT+2', 'hour' => 2],
        ['id' => 4, 'name' => 'GMT+3', 'hour' => 3],
        ['id' => 5, 'name' => 'GMT+4', 'hour' => 4],
        ['id' => 6, 'name' => 'GMT+5', 'hour' => 5],
        ['id' => 7, 'name' => 'GMT+6', 'hour' => 6],
        ['id' => 8, 'name' => 'GMT+7', 'hour' => 7],
        ['id' => 9, 'name' => 'GMT+8', 'hour' => 8],
        ['id' => 10, 'name' => 'GMT+9', 'hour' => 9],
        ['id' => 11, 'name' => 'GMT+10', 'hour' => 10],
        ['id' => 12, 'name' => 'GMT+11', 'hour' => 11],
        ['id' => 13, 'name' => 'GMT+12', 'hour' => 12],
        ['id' => 14, 'name' => 'GMT+13', 'hour' => 13],
        ['id' => 15, 'name' => 'GMT+14', 'hour' => 14],
        ['id' => 16, 'name' => 'GMT+15', 'hour' => 15],
        ['id' => 17, 'name' => 'GMT+16', 'hour' => 16],
        ['id' => 18, 'name' => 'GMT+17', 'hour' => 17],
        ['id' => 19, 'name' => 'GMT+18', 'hour' => 18],
        ['id' => 20, 'name' => 'GMT+19', 'hour' => 19],
        ['id' => 21, 'name' => 'GMT+20', 'hour' => 20],
        ['id' => 22, 'name' => 'GMT+21', 'hour' => 21],
        ['id' => 23, 'name' => 'GMT+22', 'hour' => 22],
        ['id' => 24, 'name' => 'GMT+23', 'hour' => 23],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();

        try {
            $table = DB::table('franchise_gmt');

            foreach ($this->data as $row) {
                $row['created_at'] = Carbon::now()->toDateTimeString();
                $row['updated_at'] = Carbon::now()->toDateTimeString();

                $table->insert($row);
            }

            DB::commit();
        }
        catch(\Exception $exception) {
            DB::rollBack();
            dd($exception);
        }
    }
}
