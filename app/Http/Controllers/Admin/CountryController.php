<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Country;
use App\Helper\Reply;
use App\Http\Requests\Admin\Client\StoreCountry;
use Yajra\DataTables\Facades\DataTables;

class CountryController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.country';
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
        $this->countries = Country::all();

        return view('admin.country.index', $this->data);
    }

    public function data()
    {
        $this->country = Country::get();

        return DataTables::of($this->$this->country)
            ->addColumn('action', function ($row) {
                $action = '';
                $action .= '<a href="' . route('admin.country.edit', $row->id) . '" class="btn btn-info btn-circle"
                data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                $action .= '&nbsp;&nbsp;<a href="javascript:;" class="btn btn-danger btn-circle sa-params"
                      data-toggle="tooltip" data-task-id="' . $row->id . '" data-original-title="Delete"><i class="fa fa-times" aria-hidden="true"></i></a>';
                return $action;
            })

            ->addColumn('category_name', function ($row) {

                $name = '<a href="' . route('admin.country.edit', $row->id) . '" class="show-task-detail">' . ucfirst($row->category_name) . '</a> ';

                return $name;
            })
            ->editColumn('category_country', function ($row) {
                $pin = '';

                $name = ucfirst($row->category_country);

                return $name;
            })
            ->editColumn('category_state', function ($row) {
                $pin = '';

                $name = ucfirst($row->category_state);

                return $name;
            })
            ->editColumn('category_phone', function ($row) {
                $pin = '';

                $name = ucfirst($row->category_phone);

                return $name;
            })


            ->rawColumns(['action', 'country', 'category_state'])
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
        return view('admin.country.create', $this->data);
    }  

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCountry $request)
    {
        $country = new Country();
        $country->name = $request->name;
        $country->nicename = $request->nicename;
        $country->iso = $request->iso;
        $country->iso3 = $request->iso3;
        $country->phonecode = $request->phonecode;
        $country->numcode = $request->numcode;
        $country->save();

        $countryData = Country::all();
        return Reply::successWithData(__('messages.categoryAdded'),['data' => $countryData]);
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
        $this->country = Country::findOrFail($id);
      //  $this->country = Country::where('id', '=', $id)->first();
        return view('admin.country.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreCountry $request, $id)
    {
        $country = Country::findOrFail($id);
        $data =  $request->all();

        $country->update($data);

        return Reply::redirect(route('admin.country.index'));
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
            Country::where('id', $id)->delete();
        }

        Country::destroy($id);
        $countryData = Country::all();
        return Reply::successWithData(__('messages.categoryDeleted'),['data'=> $countryData]);
    }
}
