<?php

namespace Database\Seeders;

use App\Models\Achievement;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AchievementsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Achievement::insert([
            ['achievement_type_id' => 1 ,'title' => 'First Lesson Watched',"created_at" => Carbon::now(),"updated_at" =>  Carbon::now()],
            ['achievement_type_id' => 1 ,'title' => '5 Lessons Watched',"created_at" => Carbon::now(),"updated_at" =>  Carbon::now()],
            ['achievement_type_id' => 1 ,'title' => '10 Lessons Watched',"created_at" => Carbon::now(),"updated_at" =>  Carbon::now()],
            ['achievement_type_id' => 1 ,'title' => '25 Lessons Watched',"created_at" => Carbon::now(),"updated_at" =>  Carbon::now()],
            ['achievement_type_id' => 1 ,'title' => '50 Lessons Watched',"created_at" => Carbon::now(),"updated_at" =>  Carbon::now()],
            ['achievement_type_id' => 2 ,'title' => 'First Comment Written',"created_at" => Carbon::now(),"updated_at" =>  Carbon::now()],
            ['achievement_type_id' => 2 ,'title' => '3 Comments Written',"created_at" => Carbon::now(),"updated_at" =>  Carbon::now()],
            ['achievement_type_id' => 2 ,'title' => '5 Comments Written',"created_at" => Carbon::now(),"updated_at" =>  Carbon::now()],
            ['achievement_type_id' => 2 ,'title' => '10 Comments Written',"created_at" => Carbon::now(),"updated_at" =>  Carbon::now()],
            ['achievement_type_id' => 2 ,'title' => '20 Comments Written',"created_at" => Carbon::now(),"updated_at" =>  Carbon::now()]]);
    }
}
