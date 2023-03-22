<?php

use Illuminate\Database\Seeder;

class LanguagesSeeder extends Seeder
{
    private $data = [
        [
            'code' => 'ru',
            'name' => 'Русский',
            'image' => '/img/languages/ru.png',
            'currency_code' => 'RUB',
            'default' => 1,
            'enabled' => 1,
            'position' => 0
        ],
        [
            'code' => 'en',
            'name' => 'Английский',
            'image' => '/img/languages/en.png',
            'currency_code' => 'USD',
            'default' => 0,
            'enabled' => 1,
            'position' => 1
        ],
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        echo "Seeding Languages\r\n";

        $languagesTable = DB::table(Config::get('migrations.Languages'));

        foreach ($this->data as $row) {
            $row['created_at'] = Carbon\Carbon::now()->toDateTimeString();
            $row['updated_at'] = Carbon\Carbon::now()->toDateTimeString();

            $languagesTable->insert($row);
        }
    }
}