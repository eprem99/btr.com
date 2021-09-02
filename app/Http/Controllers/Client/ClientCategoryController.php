<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Country;
use App\State;
use App\ClientCategory;
use App\ClientDetails;
use App\Helper\Reply;
use App\Http\Requests\Admin\Client\StoreClientCategory;

class ClientCategoryController extends ClientBaseController
{
 
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.company';
        $this->pageIcon = 'ti-layout-column3';
        $this->middleware(function ($request, $next) {
            if ($this->user->can('add-client')) {
                abort(403);
            }
            return $next($request);
        });
    }
 
 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->categories = ClientCategory::all();
        return view('client.clients.create_category', $this->data);
    }   
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreClientCategory $request)
    {
        $category = new ClientCategory();
        $category->category_name = $request->category_name;
        $category->save();
        $categoryData = ClientCategory::all();
        return Reply::successWithData(__('messages.categoryAdded'),['data' => $categoryData]);
    }
   
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $this->clientDetail = ClientDetails::where('user_id', '=', $this->user->id)->first();
        $this->category = ClientCategory::where('id', '=',$this->clientDetail->category_id)->first();
        $this->countries = Country::all();
        return view('client.clients.create_category_edit', $this->data);
    }

   /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function state(Request $request, $id)
    {
        $this->clientDetail = ClientCategory::where('id', '=', $id)->first();
      //  $this->clientDetail = ClientDetails::where('user_id', '=', $id)->first();
      //  dd($this->clientDetail->state);
        if($request->category_country != 0 || $request->category_country != ''){
            $states = State::where('country_id', '=', $request->category_country)->get();
            $option = '' ;
             $option .= '<option value=""> -- Select -- </option>';
                 foreach($states as $state){
                     if($this->clientDetail->category_state == $state->id){
                         $option .= '<option selected value="'.$state->id.'">'.$state->names.'</option>';
                     }else{
                         $option .= '<option value="'.$state->id.'">'.$state->names.'</option>';
                     }
                 }
        }else{
            $states = State::where('country_id', '=', $id)->get();
            $option = '' ;
             $option .= '<option value=""> -- Select -- </option>';
                 foreach($states as $state){
                    $option .= '<option value="'.$state->id.'">'.$state->names.'</option>';
                 }
        }
        return Reply::dataOnly(['data'=> $option]);
// dd($id);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreClientCategory $request)
    {
        $this->clientDetail = ClientDetails::where('user_id', '=', $this->user->id)->first();
        $id = $this->clientDetail->category_id;
        $company = ClientCategory::findOrFail($id);
        $data =  $request->all();

        $company->update($data);

        return Reply::redirect(route('client.company.edit'));
    }

}
