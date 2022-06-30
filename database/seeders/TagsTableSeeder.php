<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(Tag::count()){
            Tag::truncate();
        }

        $tags = [
            'عمومی' ,
            'خبری' ,
            'علم و تکنولوژی' ,
            'ورزشی' ,
            'بانوان' ,
            'بازی' ,
            'طنز' ,
            'آموزشی' ,
            'تفریحی' ,
            'فیلم' ,
            'مذهبی' ,
            'موسیقی' ,
            'سیاسی' ,
            'حوادث' ,
            'گردشگری' ,
            'حیوانات' ,
            'متفرقه' ,
        ]; 

        foreach($tags as $tagName){
            Tag::create(['title' => $tagName]);
        }
        $this->command->info('add thes tags ' . implode(', ', $tags));
    }
}
 