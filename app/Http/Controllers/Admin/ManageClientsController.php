<?php

namespace App\Http\Controllers\Admin;

use App\ClientDetails;
use App\Country;
use App\DataTables\Admin\ClientsDataTable;
use App\Helper\Reply;
use App\Http\Requests\Admin\Client\StoreClientRequest;
use App\Http\Requests\Admin\Client\UpdateClientRequest;
use App\Http\Requests\Gdpr\SaveConsentUserDataRequest;
use App\TaskboardColumn;
use App\ClientCategory;
use App\Invoice;
use App\Lead;
use App\Payment;
use App\PurposeConsent;
use App\PurposeConsentUser;
use App\Traits\CurrencyExchange;
use App\UniversalSearch;
use App\User;
use App\Task;
use App\ContractType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ManageClientsController extends AdminBaseController
{
    use CurrencyExchange;

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.clients';
        $this->pageIcon = 'icon-people';
        $this->middleware(function ($request, $next) {
            if (!in_array('clients', $this->user->modules)) {
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
            $this->categories = ClientCategory::all();
            $this->clients = User::allClients();
            $this->tasks = Task::all();
            $this->contracts = ContractType::all();
            $this->countries = Country::all();

            $this->totalClients = count($this->clients);
        }

        return $dataTable->render('admin.clients.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($leadID = null)
    {
        $this->countries = Country::all();

        $client = new ClientDetails();
        $this->categories = ClientCategory::all();
        $this->fields = $client->getCustomFieldGroupsWithFields()->fields;

        if (request()->ajax()) {
            return view('admin.clients.ajax-create', $this->data);
        }

        return view('admin.clients.create', $this->data);
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

        unset($data['phone_code']);
        $data['country_id'] = $request->input('phone_code');
        $data['name'] = $request->input('salutation')." ".$request->input('name');
        $data['category_id'] = $request->input('category_id');
        $data['state_id'] = $request->input('state_id');
        $user = User::create($data);
        $user->client_details()->create($data);

        $user->attachRole(3);

        cache()->forget('all-clients');


        if ($request->has('ajax_create')) {
            $teams = User::allClients();
            $teamData = '';

            foreach ($teams as $team) {
                $teamData .= '<option value="' . $team->id . '"> ' . ucwords($team->name) . ' </option>';
            }

            return Reply::successWithData(__('messages.clientAdded'), ['teamData' => $teamData]);
        }


        return Reply::redirect(route('admin.clients.index'));
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
        $this->clientDetail = ClientDetails::where('user_id', '=', $id)->first();
      
        $this->clientStats = $this->clientStats($id);

        return view('admin.clients.show', $this->data);
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
        $this->clientDetail = ClientDetails::where('user_id', '=', $this->userDetail->id)->first();
        $this->countries = Country::all();
        $this->categories = ClientCategory::all();

        return view('admin.clients.edit', $this->data);
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

        unset($data['password']);
        if ($request->password != '') {
            $data['password'] = Hash::make($request->input('password'));
        }
        $data['country_id'] = $request->input('phone_code');
        $user->update($data);

        if ($user->client_details) {
            $data['category_id'] = $request->input('category_id');
             $data['state_id'] = $request->input('state_id');
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
        return Reply::redirect(route('admin.clients.index'));
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

    public function showProjects($id)
    {
        $this->client = User::withoutGlobalScope('active')->findOrFail($id);
        $this->clientDetail = ClientDetails::where('user_id', '=', $this->client->id)->first();
        $this->clientStats = $this->clientStats($id);
       
        $taskBoardColumn = TaskboardColumn::completeColumn();

        $this->taskCompleted = Task::join('task_users', 'task_users.task_id', '=', 'tasks.id')
            ->where('task_users.user_id', $id)
            ->where('tasks.board_column_id', $taskBoardColumn->id)
            ->count();
         // return $dataTable->render('admin.clients.projects', $this->data);
        return view('admin.clients.projects', $this->data);
    }

    public function tasks($userId, $hideCompleted)
    {
        $tasks = Task::join('task_users', 'task_users.task_id', '=', 'tasks.id')
            ->join('taskboard_columns', 'taskboard_columns.id', '=', 'tasks.board_column_id')
            ->select('tasks.id', 'tasks.heading', 'tasks.due_date', 'taskboard_columns.column_name', 'taskboard_columns.label_color')
            ->where('task_users.user_id', $userId);

        if ($hideCompleted == '1') {
            $tasks->where('tasks.status', '=', 'incomplete');
        }

        $tasks->get();

        return DataTables::of($tasks)
            ->editColumn('due_date', function ($row) {
                if ($row->due_date->isPast()) {
                    return '<span class="text-danger">' . $row->due_date->format($this->global->date_format) . '</span>';
                }
                return '<span class="text-success">' . $row->due_date->format($this->global->date_format) . '</span>';
            })
            ->editColumn('heading', function ($row) {
                $name = '<a href="javascript:;" data-task-id="' . $row->id . '" class="show-task-detail">' . ucfirst($row->heading) . '</a>';

                return $name;
            })
            ->editColumn('column_name', function ($row) {
                return '<label class="label" style="background-color: ' . $row->label_color . '">' . $row->column_name . '</label>';
            })

            ->rawColumns(['column_name', 'due_date', 'heading'])
            ->make(true);
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
        return view('admin.clients.invoices', $this->data);
    }



    public function consentPurposeData($id)
    {
        $purpose = PurposeConsentUser::select('purpose_consent.name', 'purpose_consent_users.created_at', 'purpose_consent_users.status', 'purpose_consent_users.ip', 'users.name as username', 'purpose_consent_users.additional_description')
            ->join('purpose_consent', 'purpose_consent.id', '=', 'purpose_consent_users.purpose_consent_id')
            ->leftJoin('users', 'purpose_consent_users.updated_by_id', '=', 'users.id')
            ->where('purpose_consent_users.client_id', $id);

        // dd($purpose->first()->created_at);

        return DataTables::of($purpose)
            ->editColumn('status', function ($row) {
                if ($row->status == 'agree') {
                    $status = __('modules.gdpr.optIn');
                } else if ($row->status == 'disagree') {
                    $status = __('modules.gdpr.optOut');
                } else {
                    $status = '';
                }

                return $status;
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at->format($this->global->date_format) : '-';
            })
            ->make(true);
    }

    public function saveConsentLeadData(SaveConsentUserDataRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $consent = PurposeConsent::findOrFail($request->consent_id);

        if ($request->consent_description && $request->consent_description != '') {
            $consent->description = $request->consent_description;
            $consent->save();
        }

        // Saving Consent Data
        $newConsentLead = new PurposeConsentUser();
        $newConsentLead->client_id = $user->id;
        $newConsentLead->purpose_consent_id = $consent->id;
        $newConsentLead->status = trim($request->status);
        $newConsentLead->ip = $request->ip();
        $newConsentLead->updated_by_id = $this->user->id;
        $newConsentLead->additional_description = $request->additional_description;
        $newConsentLead->save();

        $url = route('admin.clients.gdpr', $user->id);

        return Reply::redirect($url);
    }

    public function clientStats($id)
    {
        $completedTaskColumn = TaskboardColumn::completeColumn();
        $clientData = DB::table('users')
            ->select(
                DB::raw('(select count(tasks.id) from `tasks` inner join task_users on task_users.task_id=tasks.id where tasks.board_column_id=' . $completedTaskColumn->id . ' and task_users.user_id = ' . $id . ') as totalCompletedTasks'),
                DB::raw('(select count(tasks.id) from `tasks` inner join task_users on task_users.task_id=tasks.id where task_users.user_id = ' . $id . ') as totalAllTasks'),
                DB::raw('(select count(tasks.id) from `tasks` inner join task_users on task_users.task_id=tasks.id where tasks.board_column_id!=' . $completedTaskColumn->id . ' and task_users.user_id = ' . $id . ') as totalPendingTasks')
            )
            ->first();
        $projectData = Payment::join('currencies', 'currencies.id', '=', 'payments.currency_id')
            ->join('projects', 'projects.id', '=', 'payments.project_id')
            ->join('users', 'users.id', '=', 'projects.client_id')
            ->where('payments.status', 'complete')
            ->where('users.id', $id)
            ->orderBy('payments.paid_on', 'ASC')
            ->select(
                'payments.amount  as total',
                'payments.id  as paymentid',
                'currencies.currency_code',
                'currencies.is_cryptocurrency',
                'currencies.usd_price',
                'currencies.exchange_rate',
                'users.name'
            );

        $invoices = Payment::join('currencies', 'currencies.id', '=', 'payments.currency_id')
            ->join('invoices', 'invoices.id', '=', 'payments.invoice_id')
            ->join('users', 'users.id', '=', 'invoices.client_id')
            ->where('payments.status', 'complete')
            ->where('users.id', $id)
            ->orderBy('payments.paid_on', 'ASC')
            ->select(
                'payments.amount  as total',
                'payments.id  as paymentid',
                'currencies.currency_code',
                'currencies.is_cryptocurrency',
                'currencies.usd_price',
                'currencies.exchange_rate',
                'users.name'
            )->union($projectData)->groupBy('paymentid')->get();

        $earnings = 0;
        $chartDataClients = array();
        foreach ($invoices as $chart) {
            if ($chart->currency_code != $this->global->currency->currency_code) {
                if ($chart->is_cryptocurrency == 'yes') {
                    if ($chart->exchange_rate == 0) {
                        if ($this->updateExchangeRates()) {
                            $usdTotal = ($chart->total * $chart->usd_price);
                            $earnings += floor($usdTotal / $chart->exchange_rate);
                        }
                    } else {
                        $usdTotal = ($chart->total * $chart->usd_price);
                        $earnings +=  floor($usdTotal / $chart->exchange_rate);
                    }
                } else {
                    if ($chart->exchange_rate == 0) {
                        if ($this->updateExchangeRates()) {
                            $earnings += floor($chart->total / $chart->exchange_rate);
                        }
                    } else {
                        $earnings += floor($chart->total / $chart->exchange_rate);
                    }
                }
            } else {
                $earnings +=  round($chart->total, 2);
            }
        }

        return [$clientData, $earnings];
    }
}
