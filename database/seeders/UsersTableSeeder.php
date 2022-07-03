<?php

namespace Database\Seeders;

use App\Models\Channel;
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
        if (User::count()) {
            Channel::truncate();
            User::truncate();
        }

        $this->createAdminUser();

        for ($i = 1; $i < 5; $i++) {
            $this->createUser($i);
        }
    }

    private function createAdminUser()
    {
        $user = User::factory()->create([
            'type' => User::TYPE_ADMIN,
            'name' => 'مدیر اصلی',
            'email' => 'admin@aparat.me',
            'mobile' => '+989000000000',
        ]);

        $user->save();

        $this->command->info('کاربر ادمین اصلی سایت ایجاد شد');
    }

    private function createUser($num = 1)
    {
        $user = User::factory()->create([
            'name' => 'کاربر ' . $num,
            'email' => 'user' . $num . '@aparat.me',
            'mobile' => '+989' . str_repeat($num, 9),
        ]);

        $user->save();

        $this->command->info('کاربر ' . $user->mobile . ' به سیستم اضافه شد');
    }
}
