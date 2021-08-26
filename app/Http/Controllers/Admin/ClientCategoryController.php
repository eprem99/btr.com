<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ClientCategory;
use App\ClientDetails;
use App\Country;
use App\Helper\Reply;
use App\Http\Requests\Admin\Client\StoreClientCategory;

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
        dd($this->categories);

        return view('admin.clients.companyindex', $this->data);
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
    public function update(Request $request, $id)
    {
        $company = ClientCategory::findOrFail($id);
        $data =  $request->all();

        $company->update($data);

        return Reply::redirect(route('admin.clients.edit_category'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ClientCategory::destroy($id);
        $categoryData = ClientCategory::all();
        return Reply::successWithData(__('messages.categoryDeleted'),['data'=> $categoryData]);
    }
}
