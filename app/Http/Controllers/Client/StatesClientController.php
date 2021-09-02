<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\State;
use App\Country;
use App\Helper\Reply;
use App\Http\Requests\Admin\Client\StoreState;
use App\TaskLabelList;

class StatesClientController extends ClientBaseController
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    public function data()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }  

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreState $request)
    {
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
    public function edit($id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreState $request, $id)
    {

    }

    public function country(Request $request, $id)
    {
     // dd($id);
        $states = State::where('country_id', '=', $request->site_country)->get();
        $selected = TaskLabelList::find($id);
       // dd($selected);
        $statik = json_decode($selected->contacts, true);
       // dd($statik);
       $option = '' ;
       $option .= '<option selected value=""> -- Select -- </option>';
            foreach($states as $state){
                if($statik['site_state'] == $state->id){
                    $option .= '<option selected value="'.$state->id.'">'.$state->names.'</option>';
                }else{
                    $option .= '<option value="'.$state->id.'">'.$state->names.'</option>';
                }
            }
       //     dd($option);
       return Reply::dataOnly(['data'=> $option]);
     //  return Reply::successWithData(__(''),['data'=> $option]);
       // return $option;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {

    }
}
