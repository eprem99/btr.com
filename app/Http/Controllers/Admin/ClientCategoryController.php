<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ClientCategory;
use App\ClientDetails;
use App\State;
use App\Country;
use App\Helper\Reply;
use App\Http\Requests\Admin\Client\StoreClientCategory;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ClientCategoryController extends AdminBaseController
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
        $this->categories = ClientCategory::all();
      //  dd($this->categories);
         $this->countries = Country::all();
         $this->countries = State::all();
        return view('admin.clients.companyindex', $this->data);
    }

    public function data()
    {
        $this->categories = ClientCategory::get();

        return DataTables::of($this->categories)
            ->addColumn('action', function ($row) {
                $action = '';
                $action .= '<a href="' . route('admin.company.edit', $row->id) . '" class="btn btn-info btn-circle"
                data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                $action .= '&nbsp;&nbsp;<a href="javascript:;" class="btn btn-danger btn-circle sa-params"
                      data-toggle="tooltip" data-task-id="' . $row->id . '" data-original-title="Delete"><i class="fa fa-times" aria-hidden="true"></i></a>';
                return $action;
            })

            ->addColumn('category_name', function ($row) {

                $name = '<a href="' . route('admin.company.edit', $row->id) . '" class="show-task-detail">' . ucfirst($row->category_name) . '</a> ';

                return $name;
            })
            ->editColumn('category_country', function ($row) {
                $country = '';
                $country = ucfirst($row->category_country);
                if($country){
                    $country = Country::where('id', '=', $row->category_country )->first();
                }
                return $country->name;
            })
            ->editColumn('category_state', function ($row) {
                $state = '';
                $state = ucfirst($row->category_country);
                if($state){
                    $state = State::where('id', '=', $row->category_state )->first();
                }

                return $state->names;

            })
            ->editColumn('category_phone', function ($row) {
                $pin = '';

                $name = ucfirst($row->category_phone);

                return $name;
            })


            ->rawColumns(['action', 'category_name', 'category_country', 'category_state', 'category_phone'])
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
        $this->categories = ClientCategory::all();
        $this->countries = Country::all();
        return view('admin.clients.create_category', $this->data);
    }  
        /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function creates()
    {
        $this->countries = Country::all();
        return view('admin.clients.creates_category', $this->data);
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
        $category->category_address = $request->category_address;
        $category->category_country = $request->category_country;
        $category->category_email = $request->category_email;
        $category->category_phone = $request->category_phone;
        $category->save();
        $categoryData = ClientCategory::all();
        return Reply::successWithData(__('messages.categoryAdded'),['data' => $categoryData]);
    }
   
    public function stores(StoreClientCategory $request)
    {
        $category = new ClientCategory();
        $category->category_name = $request->category_name;
        $category->category_address = $request->category_address;
        $category->category_country = $request->category_country;
        $category->category_email = $request->category_email;
        $category->category_phone = $request->category_phone;
        if($request->category_suite){
            $category->category_suite = $request->category_suite;
        }
        if($request->category_state){
            $category->category_state = $request->category_state;
        }
        if($request->category_city){
            $category->category_city = $request->category_city;
        }
        if($request->category_zip){
            $category->category_zip = $request->category_zip;
        }
        if($request->category_altphone){
            $category->category_altphone = $request->category_altphone;
        }
        if($request->category_fax){
            $category->category_fax = $request->category_fax;
        }


        $category->save();
        
        return Reply::redirect(route('admin.company.index'), __('messages.categoryAdded'));
      //  return Reply::successWithData(__('messages.categoryAdded'),['data' => $categoryData]);
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
       // $this->clientDetail = ClientDetails::where()->first();
        $this->category = ClientCategory::where('id', '=', $id)->first();
        $this->countries = Country::all();
        return view('admin.clients.edit_category', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreClientCategory $request, $id)
    {
        $company = ClientCategory::findOrFail($id);
        $data =  $request->all();

        $company->update($data);

        return Reply::redirect(route('admin.company.index'));
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
            ClientCategory::where('id', $id)->delete();
        }

        ClientCategory::destroy($id);
        $categoryData = ClientCategory::all();
        return Reply::successWithData(__('messages.categoryDeleted'),['data'=> $categoryData]);
    }


    public function country(Request $request, $id)
    {
       
        //  dd($request->country_id);
          if($request->category_country != 0 || $request->category_country != ''){
              $states = State::where('country_id', '=', $request->category_country)->get();
              $option = '' ;
               $option .= '<option value=""> -- Select -- </option>';
                   foreach($states as $state){
                       if($request->site_state == $state->id){
                           $option .= '<option selected value="'.$state->id.'">'.$state->names.'</option>';
                       }else{
                           $option .= '<option value="'.$state->id.'">'.$state->names.'</option>';
                       }
                   }
          }else{
            $category = ClientCategory::where('id', '=', $id)->first();

            $states = State::where('country_id', '=', $category->category_country)->get();

            $option = '' ;
             $option .= '<option value=""> -- Select -- </option>';
            // dd($this->clientDetail);
                 foreach($states as $state){
                     if($category->category_state == $state->id){
                         $option .= '<option selected value="'.$state->id.'">'.$state->names.'</option>';
                     }else{
                         $option .= '<option value="'.$state->id.'">'.$state->names.'</option>';
                     }
                 }
          }
  
          return Reply::dataOnly(['data'=> $option]);
    }

}
