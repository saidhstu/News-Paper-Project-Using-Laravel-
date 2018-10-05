<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    //Populate user roles
	    \DB::table('users_role')->insert(['id' => 1, 'name' => 'guest']);
	    \DB::table('users_role')->insert(['id' => 2, 'name' => 'user']);
	    \DB::table('users_role')->insert(['id' => 3, 'name' => 'admin']);
	    //Populate filter tables
	    \DB::table('item_keytype')->insert(['id' => 1, 'name' => 'ignore']);
	    \DB::table('item_keytype')->insert(['id' => 2, 'name' => 'article_title']);
	    \DB::table('item_keytype')->insert(['id' => 3, 'name' => 'article_link']);
	    \DB::table('item_keytype')->insert(['id' => 4, 'name' => 'article_description']);
	    \DB::table('item_keytype')->insert(['id' => 5, 'name' => 'article_date']);
	    \DB::table('item_keytype')->insert(['id' => 6, 'name' => 'article_image']);
	    \DB::table('item_keytype')->insert(['id' => 7, 'name' => 'article_category']);
	    \DB::table('item_keytype')->insert(['id' => 8, 'name' => 'article_author']);
	    \DB::table('item_keytype')->insert(['id' => 9, 'name' => 'article_price']);
	    \DB::table('item_keytype')->insert(['id' => 10, 'name' => 'article_externalId']);
	    \DB::table('item_keytype')->insert(['id' => 11, 'name' => 'article_company']);
	    \DB::table('item_keytype')->insert(['id' => 12, 'name' => 'article_language']);
	    \DB::table('item_keytype')->insert(['id' => 13, 'name' => 'article_location']);
	    \DB::table('item_filtertype')->insert(['id' => 1, 'name' => 'ignore']);
	    \DB::table('item_filtertype')->insert(['id' => 2, 'name' => 'text_selection']);
	    \DB::table('item_filtertype')->insert(['id' => 3, 'name' => 'number_bounds']);
    }
}
