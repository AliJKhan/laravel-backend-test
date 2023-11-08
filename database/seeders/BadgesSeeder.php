<?php

namespace Database\Seeders;

use App\Models\Badge;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BadgesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Badge::insert([
            ['title' => "Beginner" ,"created_at" => Carbon::now(),"updated_at" =>  Carbon::now()],
            ['title' => "Intermediate","created_at" => Carbon::now(),"updated_at" =>  Carbon::now()],
            ['title' => "Advanced","created_at" => Carbon::now(),"updated_at" =>  Carbon::now()],
            ['title' => "Master" ,"created_at" => Carbon::now(),"updated_at" =>  Carbon::now()]]);
    }
}
