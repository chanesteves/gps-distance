<?php

use App\Api;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ApisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create permanent API token
        Api::create(["key" => Str::random(60)]);
    }
}
