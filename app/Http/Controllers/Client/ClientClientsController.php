<?php

namespace App\Http\Controllers\Client;

use App\ClientDetails;
use App\Helper\Reply;
use Illuminate\Http\Request;
use App\DataTables\Admin\ClientsDataTable;
use App\Http\Requests\Admin\Client\StoreClientRequest;
use App\Http\Requests\Admin\Client\UpdateClientRequest;
use App\Http\Requests\Gdpr\SaveConsentUserDataRequest;
use App\User;
use App\ClientCategory;
use App\Country;
use App\State;
use Illuminate\Support\Facades\Hash;

class ClientClientsController extends ClientBaseController
{


    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.clients';
        $this->pageIcon = 'icon-people';
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
    public function index(ClientsDataTable $dataTable)
    {
        if (!request()->ajax()) {
            $this->clientDetail = ClientDetails::where('user_id', '=', $this->user->id)->first();
            $this->categories = ClientCategory::all();
            $this->clients = User::allClients()->where('client_details.category_id', '=', $this->clientDetail->category_id);
            $this->countries = Country::all();
            $this->totalClients = count($this->clients);
        }

        return $dataTable->render('client.clients.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->clientDetail = ClientDetails::where('user_id', '=', $this->user->id)->first();
        $this->countries = Country::all();

        $client = new ClientDetails();
        $this->categories = ClientCategory::all();
        $this->fields = $client->getCustomFieldGroupsWithFields()->fields;

        return view('client.clients.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreClientRequest $request)
    {

        $data = $request->all();
        $data['password'] = Hash::make($request->input('password'));
        $this->clientDetail = ClientDetails::where('user_id', '=', $this->user()->id);
        unset($data['phone_code']);
        $data['country'] = $request->input('country');
        $data['state'] = $request->input('state');
        $data['name'] = $request->input('salutation')." ".$request->input('name');
        $data['category_id'] = $this->clientDetail->category_id;
        $user = User::create($data);
        $user->client_details()->create($data);

        $user->attachRole(3);

        cache()->forget('all-clients');

        return Reply::redirect(route('client.clients.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->categories = ClientCategory::all();
        $this->client = User::withoutGlobalScope('active')->findOrFail($id);
        $this->clientDetail = ClientDetails::where('user_id', '=', $this->client->id)->first();
        $this->clientStats = $this->clientStats($id);

        if (!is_null($this->clientDetail)) {
            $this->clientDetail = $this->clientDetail->withCustomFields();
            $this->fields = $this->clientDetail->getCustomFieldGroupsWithFields()->fields;
        }
        return view('client.clients.show', $this->data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->userDetail = User::withoutGlobalScope('active')->findOrFail($id);
        $this->clientDetail = ClientDetails::where('user_id', '=', $id)->first();
       // dd($this->clientDetail);
        $this->countries = Country::all();
        $this->categories = ClientCategory::all();
        $this->user = $this->user()->categpory_id;
        if (!is_null($this->clientDetail)) {
            $this->clientDetail = $this->clientDetail->withCustomFields();
            $this->fields = $this->clientDetail->getCustomFieldGroupsWithFields()->fields;
        }
        return view('client.clients.edit', $this->data);
    }
   /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function state(Request $request, $id)
    {
        $this->clientDetail = ClientDetails::where('user_id', '=', $id)->first();
      //  dd($this->clientDetail->state);
        if($request->country != 0 || $request->country != ''){
            $states = State::where('country_id', '=', $request->country)->get();
            $option = '' ;
             $option .= '<option value=""> -- Select -- </option>';
                 foreach($states as $state){
                     if($this->clientDetail->state == $state->id){
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


    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateClientRequest $request, $id)
    {
        $user = User::withoutGlobalScope('active')->findOrFail($id);
        $data =  $request->all();
        $this->clientDetail = ClientDetails::where('user_id', '=', $this->user()->id);
        unset($data['password']);
        unset($data['phone_code']);
        if ($request->password != '') {
            $data['password'] = Hash::make($request->input('password'));
        }

        $user->update($data);

        if ($user->client_details) {

            $data['category_id'] = $this->clientDetail->category_id;
            $data['country'] = $request->input('country');
            $data['state'] = $request->input('state');
            $fields = $request->only($user->client_details->getFillable());
            $user->client_details->fill($fields);
            $user->client_details->save();
        } else {
            $user->client_details()->create($data);
        }


        // To add custom fields data
        if ($request->get('custom_fields_data')) {
            $user->client_details->updateCustomFieldData($request->get('custom_fields_data'));
        }
        return Reply::redirect(route('client.clients.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $universalSearches = UniversalSearch::where('searchable_id', $id)->where('module_type', 'client')->get();
        if ($universalSearches) {
            foreach ($universalSearches as $universalSearch) {
                UniversalSearch::destroy($universalSearch->id);
            }
        }
        User::destroy($id);
        return Reply::success(__('messages.clientDeleted'));
    }


    public function showInvoices($id)
    {
        $this->client = User::withoutGlobalScope('active')->findOrFail($id);
        $this->clientDetail = ClientDetails::where('user_id', '=', $this->client->id)->first();
        $this->clientStats = $this->clientStats($id);

        if (!is_null($this->clientDetail)) {
            $this->clientDetail = $this->clientDetail->withCustomFields();
            $this->fields = $this->clientDetail->getCustomFieldGroupsWithFields()->fields;
        }

        $this->invoices = Invoice::leftJoin('projects', 'projects.id', '=', 'invoices.project_id')
            ->join('currencies', 'currencies.id', '=', 'invoices.currency_id')
            ->selectRaw('invoices.invoice_number, invoices.total, currencies.currency_symbol, invoices.issue_date, invoices.id,invoices.credit_note, invoices.status,
            ( select payments.amount from payments where invoice_id = invoices.id) as paid_payment')
            ->where(function ($query) use ($id) {
                $query->where('projects.client_id', $id)
                    ->orWhere('invoices.client_id', $id);
            })
            ->orderBy('invoices.id', 'desc')
            ->get();
        return view('client.clients.invoices', $this->data);
    }

}
