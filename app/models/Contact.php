<?php

class Contact extends Eloquent {
    protected $guarded = array();

    public static $rules = array();

    public static function getContacts() {
		//$id = LMongo::collection('users')->where('email', Auth::user()->email)->first();
		return  LMongo::collection('contacts')->get();
		//return "Stub: Contacts: model";
	}
}