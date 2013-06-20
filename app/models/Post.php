<?php


class Post extends Eloquent {
	protected $guarded = array('id', 'account_id');

	public static function get_posts() {
		$id = LMongo::collection('users')->where('email', Auth::user()->email)->first();
		return  LMongo::collection('posts')->where('user_id', $id['_id'])->get();
	}

}