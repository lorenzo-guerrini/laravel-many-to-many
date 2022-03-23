<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Tag;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tag_names = ['carne', 'vegan', 'gluten free', 'pesce'];
        foreach ($tag_names as $tag_name) {
            $new_tag = new Tag();
            $new_tag->name = $tag_name;
            $new_tag->slug = Str::of($tag_name)->slug("-");
            $new_tag->save();
        }
    }
}
