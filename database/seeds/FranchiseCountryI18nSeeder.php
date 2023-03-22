<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class FranchiseCountryI18nSeeder extends Seeder
{
    private $data = [
        ['country_id' => 3, 'title' => 'Албания'],
        ['country_id' => 4, 'title' => 'Армения'],
        ['country_id' => 5, 'title' => 'Азербайджан'],
        ['country_id' => 6, 'title' => 'Беларусь'],
        ['country_id' => 7, 'title' => 'Бельгия'],
        ['country_id' => 8, 'title' => 'Болгария'],
        ['country_id' => 9, 'title' => 'Канада'],
        ['country_id' => 10, 'title' => 'Китайская Народная Республика'],
        ['country_id' => 11, 'title' => 'Хорватия'],
        ['country_id' => 12, 'title' => 'Куба'],
        ['country_id' => 13, 'title' => 'Чехия'],
        ['country_id' => 14, 'title' => 'Дания'],
        ['country_id' => 15, 'title' => 'Англия'],
        ['country_id' => 16, 'title' => 'Франция'],
        ['country_id' => 17, 'title' => 'Грузия'],
        ['country_id' => 18, 'title' => 'Германия'],
        ['country_id' => 19, 'title' => 'Греция'],
        ['country_id' => 20, 'title' => 'Гонконг (Китай)'],
        ['country_id' => 21, 'title' => 'Венгрия'],
        ['country_id' => 22, 'title' => 'Израиль'],
        ['country_id' => 23, 'title' => 'Италия'],
        ['country_id' => 24, 'title' => 'Казахстан'],
        ['country_id' => 25, 'title' => 'Кыргызстан'],
        ['country_id' => 26, 'title' => 'Латвия'],
        ['country_id' => 27, 'title' => 'Литва'],
        ['country_id' => 28, 'title' => 'Молдова'],
        ['country_id' => 29, 'title' => 'Монголия'],
        ['country_id' => 30, 'title' => 'Польша'],
        ['country_id' => 31, 'title' => 'Румыния'],
        ['country_id' => 32, 'title' => 'Россия'],
        ['country_id' => 33, 'title' => 'Шотландия'],
        ['country_id' => 34, 'title' => 'Швеция'],
        ['country_id' => 35, 'title' => 'Швейцария'],
        ['country_id' => 36, 'title' => 'Таджикистан'],
        ['country_id' => 37, 'title' => 'Турция'],
        ['country_id' => 38, 'title' => 'Туркменистан'],
        ['country_id' => 39, 'title' => 'Украина'],
        ['country_id' => 40, 'title' => 'Соединенные Штаты Америки'],
        ['country_id' => 41, 'title' => 'Узбекистан'],
        ['country_id' => 42, 'title' => 'Вьетнам'],
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
            $table = DB::table('franchise_country_i18n');

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
