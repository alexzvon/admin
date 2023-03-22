<?php

use Illuminate\Database\Seeder;

class PriceTypesSeeder extends Seeder
{
    private $data = [
        [
            "name" => "purchase",
            "title" => "Закупочная цена",
            "description" => "",
            "enabled" => true,
            "position" => 0
        ],
        [
            "name" => "retail",
            "title" => "Розничная цена",
            "description" => "",
            "enabled" => true,
            "position" => 1
        ],
        [
            "name" => "franchisee",
            "title" => "Цена для франчайзи",
            "description" => "",
            "enabled" => true,
            "position" => 2
        ],
        [
            "name" => "franchisee-designers",
            "title" => "Цена для дизайнеров франчайзи",
            "description" => "",
            "enabled" => true,
            "position" => 3
        ],
        [
            "name" => "old",
            "title" => "Старая цена",
            "description" => "",
            "enabled" => true,
            "position" => 4
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        echo "Seeding PriceTypes\r\n";

        $priceTypesTable = DB::table(Config::get('migrations.PriceTypes'));

        foreach ($this->data as $row) {
            $row['created_at'] = Carbon\Carbon::now()->toDateTimeString();
            $row['updated_at'] = Carbon\Carbon::now()->toDateTimeString();

            $priceTypesTable->insert($row);
        }
    }
}