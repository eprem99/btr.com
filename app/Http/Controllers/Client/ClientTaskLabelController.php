<?php

namespace App\Http\Controllers\Client;

use App\DataTables\Member\LabelDataTable;
use App\Helper\Reply;
use App\Http\Requests\Admin\TaskLabel\StoreRequest;
use App\Http\Requests\Admin\TaskLabel\UpdateRequest;
use App\TaskLabel;
use App\TaskLabelList;
use App\Country;
use App\State;
use App\User;
use App\ClientDetails;

class ClientTaskLabelController extends ClientBaseController
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
        return $dataTable->render('client.task-label.index', $this->data);
    }

    public function create()
    {
        $this->clients = User::allClients();
        $this->countries = Country::all();
        return view('client.task-label.create', $this->data);
    }

    public function store(StoreRequest $request)
    {
        $taskLabel = new TaskLabelList();
        $this->storeUpdate($request, $taskLabel);
        return Reply::redirect(route('client.task-label.index'), __('messages.workorderLabel.addedSuccess'));
    }

    public function edit($id)
    {

        $this->taskLabel = TaskLabelList::find($id);
        $this->countries = Country::all();
        return view('client.task-label.edit', $this->data);
    }

    public function update(UpdateRequest $request, $id)
    {
        $taskLabel = TaskLabelList::findOrFail($id);
        $this->storeUpdate($request, $taskLabel);

        return Reply::redirect(route('client.task-label.index'), __('messages.workorderLabel.updatedSuccess'));
    }

    public function show($id)
    {
        $this->taskLabel = TaskLabelList::find($id);
        $contact = json_decode($this->taskLabel->contacts, true);
        $this->countries = Country::where('id', '=', $contact['site_country'])->first();
        $this->state = State::where('id', '=', $contact['site_state'])->first();
        return view('client.task-label.show', $this->data);
    }

    private function storeUpdate($request, $taskLabel)
    {
        $this->clientDetail = ClientDetails::where('user_id', '=', $this->user->id)->first();
        $json = json_encode($request->input());
        $taskLabel->label_name  = $request->label_name;
        $taskLabel->description = $request->description;
        $taskLabel->company     = $this->clientDetail->category_id;
        $taskLabel->user_id     = $this->user->id;
        $taskLabel->notification  = $request->site_notification;
        $taskLabel->contacts     = $json;
        $taskLabel->save();

        return $taskLabel;
    }

    public function destroy($id)
    {
        TaskLabel::where('id', $id)->delete();
        TaskLabelList::destroy($id);

        return Reply::success(__('messages.workorderLabel.deletedSuccess'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createLabel()
    {
        return view('client.task-label.create-ajax', $this->data);
    }

    public function storeLabel(StoreRequest $request)
    {
        $taskLabel = new TaskLabelList();
        $this->storeUpdate($request, $taskLabel);
        $allTaskLabels = TaskLabelList::all();

        $labels = '';
        foreach ($allTaskLabels as $key => $value) {
            $labels.= '<option>' . $value->site_name . '</label> " value="' . $value->id . '">' . $value->site_name . '</option>';
        }
        return Reply::successWithData(__('messages.workorderLabel.addedSuccess'), ['labels' => $labels]);
    }
}
