<?php

use Carbon\Carbon;
use App\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $languages = array(
            ['name' => 'English', 'locale' =>'en', 'active' => 1, 'owner' => 'admin', 'last_updated_by' => 'admin', 'created_at' => Carbon::now('UTC')->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now('UTC')->format('Y-m-d H:i:s')],
            ['name' => 'Hindi', 'locale' =>'hi', 'active' => 1, 'owner' => 'admin', 'last_updated_by' => 'admin', 'created_at' => Carbon::now('UTC')->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now('UTC')->format('Y-m-d H:i:s')],
            ['name' => 'Punjabi', 'locale' =>'pa', 'active' => 1, 'owner' => 'admin', 'last_updated_by' => 'admin', 'created_at' => Carbon::now('UTC')->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now('UTC')->format('Y-m-d H:i:s')],
            ['name' => 'Bengali', 'locale' =>'bn', 'active' => 1, 'owner' => 'admin', 'last_updated_by' => 'admin', 'created_at' => Carbon::now('UTC')->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now('UTC')->format('Y-m-d H:i:s')],
            ['name' => 'Chinese', 'locale' =>'zh', 'active' => 1, 'owner' => 'admin', 'last_updated_by' => 'admin', 'created_at' => Carbon::now('UTC')->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now('UTC')->format('Y-m-d H:i:s')],
            ['name' => 'Spanish', 'locale' =>'es', 'active' => 1, 'owner' => 'admin', 'last_updated_by' => 'admin', 'created_at' => Carbon::now('UTC')->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now('UTC')->format('Y-m-d H:i:s')],
            ['name' => 'French', 'locale' =>'fr', 'active' => 1, 'owner' => 'admin', 'last_updated_by' => 'admin', 'created_at' => Carbon::now('UTC')->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now('UTC')->format('Y-m-d H:i:s')],
            ['name' => 'German', 'locale' =>'de', 'active' => 1, 'owner' => 'admin', 'last_updated_by' => 'admin', 'created_at' => Carbon::now('UTC')->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now('UTC')->format('Y-m-d H:i:s')],
            ['name' => 'Italian', 'locale' =>'it', 'active' => 1, 'owner' => 'admin', 'last_updated_by' => 'admin', 'created_at' => Carbon::now('UTC')->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now('UTC')->format('Y-m-d H:i:s')],
            ['name' => 'Russian', 'locale' =>'ru', 'active' => 1, 'owner' => 'admin', 'last_updated_by' => 'admin', 'created_at' => Carbon::now('UTC')->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now('UTC')->format('Y-m-d H:i:s')],
            ['name' => 'Portuguese', 'locale' =>'pt', 'active' => 1, 'owner' => 'admin', 'last_updated_by' => 'admin', 'created_at' => Carbon::now('UTC')->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now('UTC')->format('Y-m-d H:i:s')],
        );

        Language::insert($languages);
    }
}
