<?php

class AuthController extends \BaseController {

	public function __construct()
    {
        //$this->beforeFilter('auth');

        $this->beforeFilter('csrf', array('on' => 'post'));

        // $this->afterFilter('log', array('only' =>
        //                     array('fooAction', 'barAction')));
    }
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index() {
		return View::make('account.login');
	}

	public function login() {
		$user = array(
			'email' => Input::get('email'), 
			'password' => Input::get('password') 
		);

		if(Auth::attempt($user)) {
    		// $posts = Post::get_posts();
    		// return View::make('hello')->with('posts',$posts);
    		return Redirect::to('account')
    			->with('flash_notice', 'You are successfully logged in');

	    } 

	    return Redirect::to('login')
            ->with('flash_error', 'Your username/password combination was incorrect.')
            ->withInput();
	}

	public function logout() {
		Auth::logout();
    	return Redirect::route('home')
        	->with('flash_notice', 'You are successfully logged out.');
	
	}
	public function profile() {
		return View::make('account.profile');
	
	}
	public function register() {
		// $input = array(
		// 	'email' => Input::get('email')
		// );

		$validator = User::validate(Input::all());

		if ($validator->passes()) {
			$user = User::create(array(
				'email'    => Input::get('email'),
				'password' => Hash::make(Input::get('password'))
			));
			//var_dump($ss);
			if($user) {
				Auth::attempt(array(
					'email'    => Input::get('email'),
					'password' => Input::get('password')
				));
	    		// $posts = Post::get_posts();
	    		// return View::make('hello')->with('posts',$posts);
	    		return Redirect::to('account')
	    			->with('flash_notice', 'You are successfully registered');
		    } 
	    } else {
	    	//$messages = $validator->messages()->first('email');
	    	return Redirect::to('register')->withErrors($validator->messages());
	    	//return Redirect::to('register')->with($p);
	    }

		// $user = new User;
		// $user->email    = Input::get('email')
		// $user->password = Hash::make(Input::get('password'));
		// $user->save();

		// $id = LMongo::collection('users')->insert(
		// 	 array('email' => $user->email, 'password' => $user->password )
		// );
		// var_dump($id);
		// return "dfdf";
	}
	public function register_form() {
		return View::make('account.register');
	}
}