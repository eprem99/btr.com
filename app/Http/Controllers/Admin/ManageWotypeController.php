<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Reply;
use App\Http\Requests\Wotype\StoreWoType;
use App\WoType;
use App\User;

class ManageWotypeController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.wotype';
        $this->pageIcon = 'icon-user';
        $this->middleware(function ($request, $next) {
            if(!in_array('employees',$this->user->modules)){
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
        $this->groups = WoType::all();
        return view('admin.wotype.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.wotype.create', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function quickCreate()
    {
        $this->teams = WoType::all();
        return view('admin.wotype.quick-create', $this->data);
    }

    /**
     * @param StoreWotype $request
     * @return array
     */
    public function store(StoreWoType $request)
    {
        $group = new WoType();
        $group->name = $request->name;
        $group->price = $request->price;
        $group->save();

        return Reply::redirect(route('admin.wotype.index'), __('messages.wotypeAdded'));
    }

    /**
     * @param StoreWotype $request
     * @return array
     */
    public function quickStore(StoreWoType $request)
    {
        $group = new WoType();
        $group->name = $request->name;
        $group->price = $request->price;
        $group->save();

        $teams = WoType::all();
        $teamData = '';

        foreach ($teams as $team) {
            $selected = '';

            if ($team->id == $group->id) {
                $selected = 'selected';
            }

            $teamData .= '<option ' . $selected . ' value="' . $team->id . '"> ' . $team->name . ' </option>';
        }

        return Reply::successWithData(__('messages.wotypeAdded'), ['teamData' => $teamData]);
    }

    /**
     * Display the specified resource.
     *[
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
        $this->group = Wotype::findOrFail($id);

        return view('admin.wotype.edit', $this->data);
    }

    /**
     * @param StoreWotype $request
     * @param $id
     * @return array
     */
    public function update(StoreWotype $request, $id)
    {
        $group = WoType::find($id);
        $group->name = $request->name;
        $group->price = $request->price;
        $group->save();

        return Reply::redirect(route('admin.wotype.index'), __('messages.wotypeUpdated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        Wotype::destroy($id);
        return Reply::dataOnly(['status' => 'success']);
    }
}
