<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\LabelDataTable;
use App\Helper\Reply;
use App\Http\Requests\Admin\TaskLabel\StoreRequest;
use App\Http\Requests\Admin\TaskLabel\UpdateRequest;
use App\TaskLabel;
use App\TaskLabelList;
use App\User;
use App\Country;
use App\state;
use App\ClientDetails;

class ManageTaskLabelController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageIcon = 'fa fa-file';
        $this->pageTitle = 'app.menu.taskLabel';
        $this->middleware(function ($request, $next) {
            if (!in_array('tasks', $this->user->modules)) {
                abort(403);
            }
            return $next($request);
        });
    }

    public function index(LabelDataTable $dataTable)
    {
        return $dataTable->render('admin.site.index', $this->data);
    }

    public function create()
    {
        $this->clients = User::allClients();
        $this->countries = Country::all();
        $this->states = State::all();
        return view('admin.site.create', $this->data);
    }

    public function store(StoreRequest $request)
    {
        $taskLabel = new TaskLabelList();
        $this->storeUpdate($request, $taskLabel);
        return Reply::redirect(route('admin.site.index'), __('messages.taskLabel.addedSuccess'));
    }

    public function edit($id)
    {
        $this->taskLabel = TaskLabelList::find($id);
        $this->clients = User::allClients();
        $this->countries = Country::all();
        $this->states = State::all();
        return view('admin.site.edit', $this->data);
    }

    public function update(UpdateRequest $request, $id)
    {
        $taskLabel = TaskLabelList::findOrFail($id);
        $this->storeUpdate($request, $taskLabel);

        return Reply::redirect(route('admin.site.index'), __('messages.taskLabel.updatedSuccess'));
    }

    public function show($id)
    {
        $this->taskLabel = TaskLabelList::find($id);
        $contact = json_decode($this->taskLabel->contacts, true);
        $this->countries = Country::where('id', '=', $contact['site_country'])->first();
        $this->state = State::where('id', '=', $contact['site_state'])->first();
        return view('admin.site.show', $this->data);
    }

    private function storeUpdate($request, $taskLabel)
    {
        $this->clientDetail = ClientDetails::where('user_id', '=', $request->user_id)->first();
        $json = json_encode($request->input());
        $taskLabel->label_name  = $request->label_name;
        $taskLabel->company     = $this->clientDetail->category_id;
        $taskLabel->user_id     = $request->user_id;
        $taskLabel->notification  = $request->site_notification;
        $taskLabel->contacts     = $json;
        $taskLabel->description = $request->description;
        $taskLabel->save();

        return $taskLabel;
    }

    public function destroy($id)
    {
        TaskLabel::where('label_id', $id)->delete();
        TaskLabelList::destroy($id);

        return Reply::success(__('messages.taskLabel.deletedSuccess'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createLabel()
    {
        return view('admin.site.create-ajax', $this->data);
    }

    public function storeLabel(StoreRequest $request)
    {
        $taskLabel = new TaskLabelList();
        $this->storeUpdate($request, $taskLabel);
        $allTaskLabels = TaskLabelList::all();

        $labels = '';
        foreach ($allTaskLabels as $key => $value) {
            $labels.= '<option data-content="<label class=\'badge b-all\' style=\'background:' . $value->label_color . '\'>' . $value->label_name . '</label> " value="' . $value->id . '">' . $value->label_name . '</option>';
        }
        return Reply::successWithData(__('messages.taskLabel.addedSuccess'), ['labels' => $labels]);
    }
}
