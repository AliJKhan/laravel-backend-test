<?php

namespace Database\Seeders;

use App\Models\AchievementType;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AchievementTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AchievementType::insert([['type' => 'Lessons Watched',"created_at" => Carbon::now(),"updated_at" =>  Carbon::now()],['type' => 'Comments Written',"created_at" => Carbon::now(),"updated_at" =>  Carbon::now()]]);
    }
}
