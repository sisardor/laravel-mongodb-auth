<?php

class ContactsController extends BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        if (Request::ajax()) {

            $users = LMongo::collection('contacts')->get();
            return $users;
        }
        else {
            $r = Contact::getContacts();
            return View::make('contacts.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        //
        $clean_name = strtolower(Input::get('name.first')) . "-" . strtolower(Input::get('name.last'));
        $clean_name =  str_replace (" ", "", $clean_name);
        $input = array(
                'added' => Input::get('added'),
                'email' => Input::get('email'),
                'notes' => Input::get('notes'),
                'number'=> Input::get('number'),
                'clean_name' => $clean_name,
                'name' => array(
                                'first' => Input::get('name.first'),
                                'last'  => Input::get('name.last')
                            )   
        );
        $id = LMongo::collection('contacts')->insert($input);
        if ($id) {
            return $id;
        } else {
            return "Error";
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        if (Request::ajax()) {
            $user = LMongo::collection('contacts')->where('clean_name', $id)->first();
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {

        //return Input::all();
        //return LMongo::collection('contacts')->insert(Input::all());
        $clean_name = strtolower(Input::get('name.first')) . "-" . strtolower(Input::get('name.last'));
        $clean_name =  str_replace (" ", "", $clean_name);
        $input = array(
                'added' => Input::get('added'),
                'email' => Input::get('email'),
                'notes' => Input::get('notes'),
                'number'=> Input::get('number'),
                'clean_name' => $clean_name,
                'name' => array(
                                'first' => Input::get('name.first'),
                                'last'  => Input::get('name.last')
                            )   
        );
        $id = LMongo::collection('contacts')
                ->where('clean_name', $id)
                ->update($input);
        if($id) {
            return LMongo::collection('contacts')->where('clean_name', $clean_name)->first();
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

}