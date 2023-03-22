<?php

use Illuminate\Database\Seeder;

class CurrenciesSeeder extends Seeder
{
    private $data = [
        [
            'code' => 'RUB',
            'name' => 'Рубли',
            'symbol' => 'руб.',
            'precision' => 2,
            'thousand_separator' => ' ',
            'decimal_separator' => '.',
            'swap_currency_symbol' => true,
            'enabled' => 1,
        ],
        [
            'code' => 'USD',
            'name' => 'Доллары',
            'symbol' => '$',
            'precision' => 2,
            'thousand_separator' => ' ',
            'decimal_separator' => '.',
            'swap_currency_symbol' => false,
            'position' => 1,
            'enabled' => 1,
        ],
        [
            'code' => 'EUR',
            'name' => 'Евро',
            'symbol' => '€',
            'precision' => 2,
            'thousand_separator' => ' ',
            'decimal_separator' => '.',
            'swap_currency_symbol' => false,
            'position' => 2,
            'enabled' => 1,
        ],

    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        echo "Seeding Currencies\r\n";

        $currenciesTable = DB::table(Config::get('migrations.Currencies'));

        foreach ($this->data as $row) {
            $row['created_at'] = Carbon\Carbon::now()->toDateTimeString();
            $row['updated_at'] = Carbon\Carbon::now()->toDateTimeString();

            $currenciesTable->insert($row);
        }
    }
}