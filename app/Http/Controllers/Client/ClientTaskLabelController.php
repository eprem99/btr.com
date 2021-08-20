<?php

namespace App\Http\Controllers\Client;

use App\DataTables\Member\LabelDataTable;
use App\Helper\Reply;
use App\Http\Requests\Admin\TaskLabel\StoreRequest;
use App\Http\Requests\Admin\TaskLabel\UpdateRequest;
use App\TaskLabel;
use App\TaskLabelList;
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
        return view('client.task-label.create', $this->data);
    }

    public function store(StoreRequest $request)
    {
        $taskLabel = new TaskLabelList();
        $this->storeUpdate($request, $taskLabel);
        return Reply::redirect(route('client.task-label.index'), __('messages.taskLabel.addedSuccess'));
    }

    public function edit($id)
    {

        $this->taskLabel = TaskLabelList::find($id);
        $this->clients = User::allClients();
        return view('client.task-label.edit', $this->data);
    }

    public function update(UpdateRequest $request, $id)
    {
        $taskLabel = TaskLabelList::findOrFail($id);
        $this->storeUpdate($request, $taskLabel);

        return Reply::redirect(route('client.task-label.index'), __('messages.taskLabel.updatedSuccess'));
    }

    public function show($id)
    {
        //
    }

    private function storeUpdate($request, $taskLabel)
    {
        $this->clientDetail = ClientDetails::where('user_id', '=', $this->user->id)->first();
        $json = json_encode($request->input());
        $taskLabel->label_name  = $request->label_name;
        $taskLabel->description = $request->description;
        $taskLabel->company     = $this->clientDetail->category_id;
        $taskLabel->user_id     = $this->user->id;
        $taskLabel->contacts     = $json;
        $taskLabel->save();

        return $taskLabel;
    }

    public function destroy($id)
    {
        TaskLabel::where('site_id', $id)->delete();
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
        return Reply::successWithData(__('messages.taskLabel.addedSuccess'), ['labels' => $labels]);
    }
}
