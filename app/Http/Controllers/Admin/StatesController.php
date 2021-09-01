<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\State;
use App\Country;
use App\Helper\Reply;
use App\Http\Requests\Admin\Client\StoreState;
use Yajra\DataTables\Facades\DataTables;

class StatesController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.state';
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
      //  $this->states = State::inerjoin('countries as ca', 'country_id', '=', 'ca.id')->get();

        $this->states = State::with('Country')->get();

        return view('admin.state.index', $this->data);
    }

    public function data()
    {
        $this->state = State::get();

        return DataTables::of($this->$this->state)
            ->addColumn('action', function ($row) {
                $action = '';
                $action .= '<a href="' . route('admin.state.edit', $row->id) . '" class="btn btn-info btn-circle"
                data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                $action .= '&nbsp;&nbsp;<a href="javascript:;" class="btn btn-danger btn-circle sa-params"
                      data-toggle="tooltip" data-task-id="' . $row->id . '" data-original-title="Delete"><i class="fa fa-times" aria-hidden="true"></i></a>';
                return $action;
            })

            ->addColumn('name', function ($row) {

                $name = '<a href="' . route('admin.state.edit', $row->id) . '" class="show-task-detail">' . ucfirst($row->name) . '</a> ';

                return $name;
            })
            ->editColumn('country', function ($row) {

                $name = ucfirst($row->country_id);

                return $name;
            })


            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->countries = Country::all();
        return view('admin.state.create', $this->data);
    }  

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreState $request)
    {
        $state = new State();
        $state->names = $request->names;
        $state->slug = $request->slug;
        $state->country_id = $request->country_id;
        $state->timezone = $request->timezone;
        $state->save();

        $stateData = State::all();
        return Reply::redirect(route('admin.state.index'), __('messages.stateAdded'));
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
        
        $this->state = State::findOrFail($id);
        $this->countries = Country::all();
      //  $this->state = State::where('id', '=', $id)->first();
        return view('admin.state.edit', $this->data);
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
        $state = State::findOrFail($id);
        $data =  $request->all();

        $state->update($data);

        return Reply::redirect(route('admin.state.index'));
    }

    public function country(Request $request, $id)
    {
         dd($id);
        $states = State::where('country_id', '=', $id)->get();
        $option = '';
            foreach($states as $state){
                $option .= '<option value="'.$state->id.'">'.$state->names.'</option>';
            }
       //     dd($option);
       return $option;
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
        if ($request->has('recurring') && $request->recurring == 'yes') {
            State::where('id', $id)->delete();
        }

        State::destroy($id);
        $stateData = State::all();
        return Reply::successWithData(__('messages.categoryDeleted'),['data'=> $stateData]);
    }
}
