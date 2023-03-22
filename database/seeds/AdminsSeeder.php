<?php

use Illuminate\Database\Seeder;

class AdminsSeeder extends Seeder
{
    private $data = [
//        [
//            'roles' => [1],
//            'name' => 'SuperAdmin',
//            'email' => 'asd2ya7asJgat65hva@mossebo.ru',
//            'password' => 'Juyiayu26ahVUat1nkx;ap1'
//        ],
//        [
//            'roles' => [2],
//            'name' => 'Владислав',
//            'email' => 'code@mossebo.ru',
//            'password' => 'XFx7pCp4GRWB'
//        ],
//        [
//            'roles' => [2],
//            'name' => 'Юрий',
//            'email' => 'code2@mossebo.ru',
//            'password' => 'bnHy28s1Laz&3l'
//        ],
//        [
//            'roles' => [3],
//            'name' => 'Диана Шамонова',
//            'email' => 'product@mossebo.market',
//            'password' => 'jd&haU'
//        ],
//        [
//            'roles' => [3],
//            'name' => 'Мария Нестерова',
//            'email' => 'product8@mossebo.market',
//            'password' => 'ua6%1jaD'
//        ],




//        [
//            'roles' => [3],
//            'name' => 'Марина Шарыгина',
//            'email' => 'art@mossebo.ru',
//            'password' => '8gQpAR2g'
//        ],
//        [
//            'roles' => [3],
//            'name' => 'Мария Нестерова',
//            'email' => 'product82@mossebo.market',
//            'password' => 'VBRFqk~Q'
//        ],
//        [
//            'roles' => [3],
//            'name' => 'Мария Нестерова',
//            'email' => 'product83@mossebo.market',
//            'password' => 'O||}zpbu'
//        ],
//        [
//            'roles' => [3],
//            'name' => 'Мария Нестерова',
//            'email' => 'product84@mossebo.market',
//            'password' => 'LfnTtt7z'
//        ],
//        [
//            'roles' => [3],
//            'name' => 'Мария Нестерова',
//            'email'3 => 'product85@mossebo.market',
//            'password' => 'We4O7?}H'
//        ],
    ];


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        echo "Seeding Admins\r\n";

        foreach ($this->data as $row) {
            (new \App\Models\Admin)->saveFromRequestData([
                'name' => $row['name'],
                'email' => $row['email'],
                'password' => encodePassword($row['password']),
                'api_token' => str_random(60)
            ]);
        }
    }
}
