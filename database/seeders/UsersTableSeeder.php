<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(User::count()){
            User::truncate();
        }

        $this->createAdminUser();
        $this->createUser();
    }

    private function createAdminUser(){
         $user = User::factory()->create([
            'type' => User::TYPE_ADMIN,
            'name' => 'مدیر اصلی',
            'email' => 'admin@aparat.me',
            'mobile' => '+989111111111'
         ]);

         $user->save();

         $this->command->info('create admin user');
    }

    private function createUser(){
        $user = User::factory()->create([
            'type' => User::TYPE_USER,
            'name' => 'کاربر1',
            'email' => 'user1@aparat.me',
            'mobile' => '+989222222222'
         ]);

         $user->save();

         $this->command->info('create default user');
    }
}
