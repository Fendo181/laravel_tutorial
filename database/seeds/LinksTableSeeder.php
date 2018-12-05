<?php

use Illuminate\Database\Seeder;

class LinksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 5人分のテストデータを作成する
        factory(App\Link::class,5)->create();
    }
}
