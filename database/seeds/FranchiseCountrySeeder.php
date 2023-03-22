<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class FranchiseCountrySeeder extends Seeder
{
    private $data = [
        ['id' => 3, 'iso_num' => '8', 'iso_2' => 'AL', 'iso_3' => 'ALB'],
        ['id' => 4, 'iso_num' => '51', 'iso_2' => 'AM', 'iso_3' => 'ARM'],
        ['id' => 5, 'iso_num' => '31', 'iso_2' => 'AZ', 'iso_3' => 'AZE'],
        ['id' => 6, 'iso_num' => '112', 'iso_2' => 'BY', 'iso_3' => 'BLR'],
        ['id' => 7, 'iso_num' => '56', 'iso_2' => 'BE', 'iso_3' => 'BEL'],
        ['id' => 8, 'iso_num' => '100', 'iso_2' => 'BG', 'iso_3' => 'BGR'],
        ['id' => 9, 'iso_num' => '124', 'iso_2' => 'CA', 'iso_3' => 'CAN'],
        ['id' => 10, 'iso_num' => '156', 'iso_2' => 'CN', 'iso_3' => 'CHN'],
        ['id' => 11, 'iso_num' => '191', 'iso_2' => 'HR', 'iso_3' => 'HRV'],
        ['id' => 12, 'iso_num' => '192', 'iso_2' => 'CU', 'iso_3' => 'CUB'],
        ['id' => 13, 'iso_num' => '203', 'iso_2' => 'CZ', 'iso_3' => 'CZE'],
        ['id' => 14, 'iso_num' => '208', 'iso_2' => 'DK', 'iso_3' => 'DNK'],
        ['id' => 15, 'iso_num' => '00', 'iso_2' => 'XE', 'iso_3' => 'XEN'],
        ['id' => 16, 'iso_num' => '250', 'iso_2' => 'FR', 'iso_3' => 'FRA'],
        ['id' => 17, 'iso_num' => '268', 'iso_2' => 'GE', 'iso_3' => 'GEO'],
        ['id' => 18, 'iso_num' => '276', 'iso_2' => 'DE', 'iso_3' => 'DEU'],
        ['id' => 19, 'iso_num' => '300', 'iso_2' => 'GR', 'iso_3' => 'GRC'],
        ['id' => 20, 'iso_num' => '344', 'iso_2' => 'HK', 'iso_3' => 'HKG'],
        ['id' => 21, 'iso_num' => '348', 'iso_2' => 'HU', 'iso_3' => 'HUM'],
        ['id' => 22, 'iso_num' => '376', 'iso_2' => 'IL', 'iso_3' => 'ISR'],
        ['id' => 23, 'iso_num' => '380', 'iso_2' => 'IT', 'iso_3' => 'ITA'],
        ['id' => 24, 'iso_num' => '398', 'iso_2' => 'KZ', 'iso_3' => 'KAZ'],
        ['id' => 25, 'iso_num' => '417', 'iso_2' => 'KG', 'iso_3' => 'KGZ'],
        ['id' => 26, 'iso_num' => '428', 'iso_2' => 'LV', 'iso_3' => 'LVA'],
        ['id' => 27, 'iso_num' => '440', 'iso_2' => 'LT', 'iso_3' => 'LTU'],
        ['id' => 28, 'iso_num' => '498', 'iso_2' => 'MD', 'iso_3' => 'MDA'],
        ['id' => 29, 'iso_num' => '496', 'iso_2' => 'MN', 'iso_3' => 'MNG'],
        ['id' => 30, 'iso_num' => '616', 'iso_2' => 'PL', 'iso_3' => 'POL'],
        ['id' => 31, 'iso_num' => '642', 'iso_2' => 'RO', 'iso_3' => 'ROU'],
        ['id' => 32, 'iso_num' => '643', 'iso_2' => 'RU', 'iso_3' => 'RUS'],
        ['id' => 33, 'iso_num' => '00', 'iso_2' => 'XS', 'iso_3' => 'XSC'],
        ['id' => 34, 'iso_num' => '752', 'iso_2' => 'SE', 'iso_3' => 'SWE'],
        ['id' => 35, 'iso_num' => '756', 'iso_2' => 'CH', 'iso_3' => 'CHE'],
        ['id' => 36, 'iso_num' => '762', 'iso_2' => 'TJ', 'iso_3' => 'TJK'],
        ['id' => 37, 'iso_num' => '792', 'iso_2' => 'TR', 'iso_3' => 'TUR'],
        ['id' => 38, 'iso_num' => '795', 'iso_2' => 'TM', 'iso_3' => 'TKM'],
        ['id' => 39, 'iso_num' => '804', 'iso_2' => 'UA', 'iso_3' => 'UKR'],
        ['id' => 40, 'iso_num' => '840', 'iso_2' => 'US', 'iso_3' => 'USA'],
        ['id' => 41, 'iso_num' => '860', 'iso_2' => 'UZ', 'iso_3' => 'UZB'],
        ['id' => 42, 'iso_num' => '704', 'iso_2' => 'VN', 'iso_3' => 'VNM'],
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
            $table = DB::table('franchise_country');

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
