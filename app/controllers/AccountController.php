<?php

class AccountController extends \BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/
	 /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {

        if (Request::ajax()) {
            $user = LMongo::collection('ads')->where('user_id', Auth::user()->id)->first();
            if(!$user) {
                return Response::json(array(
                    'code' => '404',
                    'message' => 'Not Found'
                ), 404);
            }
            return $user;
        } else {
            return 'Nice try';
        }
    }

	public function index() {
        if (Request::ajax()) {

            $ads = LMongo::collection('ads')->where('user_id', Auth::user()->id)->get();
            return $ads;
        }
        else {
            $r = Contact::getContacts();
            return View::make('contacts.index');
        }
    }
    public function update($id) {

        $cat = Input::get('cat');

		if($cat == "nko" || $cat == "nga") {
			$ad = array(
				'user_id' => Auth::user()->id,
				'title' => Input::get('title'),
				'price' => Input::get('price'),
				'text'	=> Input::get('text'),
				'land_area' => Input::get('land_area'),
				'real_square' => Input::get('real_square'),
				'rooms' => Input::get('rooms'),
				"cat" => $cat
			);

			$id = LMongo::collection('ads')
                ->where('_id', new MongoId($id))
                ->update($ad);

			//$id = LMongo::collection('ads')->insert($ad);
			return $id;

		}
		if($cat == "aup" || $cat == "aud") {
			$ad = array(
				'user_id' => Auth::user()->id,
				'title' => Input::get('title'),
				'price' => Input::get('price'),
				'text'	=> Input::get('text'),
				'auto_make' => Input::get('auto_make'),
				'auto_model' => Input::get('auto_model'),
				'auto_year' => Input::get('auto_year'),
				'auto_total_mile' => Input::get('auto_total_mile'),
				"cat" => $cat
			);
			$id = LMongo::collection('ads')
                ->where('_id', new MongoId($id))
                ->update($ad);

			//$id = LMongo::collection('ads')->insert($ad);
			return $id;

		}

    }
	public function store() {
		$cat = Input::get('cat');

		if($cat == "nko" || $cat == "nga") {
			$ad = array(
				'user_id' => Auth::user()->id,
				'title' => Input::get('title'),
				'price' => Input::get('price'),
				'text'	=> Input::get('text'),
				'land_area' => Input::get('land_area'),
				'real_square' => Input::get('real_square'),
				'rooms' => Input::get('rooms'),
				"cat" => $cat
			);

			$id = LMongo::collection('ads')->insert($ad);
			return $id;

		}
		if($cat == "aup" || $cat == "aud") {
			$ad = array(
				'user_id' => Auth::user()->id,
				'title' => Input::get('title'),
				'price' => Input::get('price'),
				'text'	=> Input::get('text'),
				'auto_make' => Input::get('auto_make'),
				'auto_model' => Input::get('auto_model'),
				'auto_year' => Input::get('auto_year'),
				'auto_total_mile' => Input::get('auto_total_mile'),
				"cat" => $cat
			);

			$id = LMongo::collection('ads')->insert($ad);
			return $id;

		}

        //
        return 'error';
    }

}
    