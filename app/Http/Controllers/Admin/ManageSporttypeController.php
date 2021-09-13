<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Reply;
use App\Http\Requests\Wotype\StoreSportType;
use App\SportType;

class ManageSportTypeController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.sporttype';
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
        $this->groups = SportType::get();
        return view('admin.sporttype.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.sporttype.create', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function quickCreate()
    {
        $this->teams = SportType::all();
        return view('admin.sporttype.quick-create', $this->data);
    }

    /**
     * @param StoreSportType $request
     * @return array
     */
    public function store(StoreSportType $request)
    {
        $group = new SportType();
        $group->team_name = $request->team_name;
        $group->save();

        return Reply::redirect(route('admin.sporttype.index'), __('messages.sporttypeAdded'));
    }

    /**
     * @param StoreSportType $request
     * @return array
     */
    public function quickStore(StoreSportType $request)
    {
        $group = new SportType();
        $group->name = $request->name;
        $group->save();

        $teams = SportType::all();
        $teamData = '';

        foreach ($teams as $team) {
            $selected = '';

            if ($team->id == $group->id) {
                $selected = 'selected';
            }

            $teamData .= '<option ' . $selected . ' value="' . $team->id . '"> ' . $team->team_name . ' </option>';
        }

        return Reply::successWithData(__('messages.sporttypeAdded'), ['teamData' => $teamData]);
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
        $this->group = SportType::findOrFail($id);

        return view('admin.sporttype.edit', $this->data);
    }

    /**
     * @param StoreSportType $request
     * @param $id
     * @return array
     */
    public function update(StoreSportType $request, $id)
    {
        $group = SportType::find($id);
        $group->name = $request->name;
        $group->save();

        

        return Reply::redirect(route('admin.sporttype.index'), __('messages.sporttypeUpdated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        SportType::destroy($id);
        return Reply::dataOnly(['status' => 'success']);
    }
}
