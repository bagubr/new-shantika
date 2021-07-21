<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScheduleNotOperate\CreateScheduleNotOperateRequest;
use App\Models\Route;
use App\Models\ScheduleNotOperate;
use Illuminate\Http\Request;

class ScheduleNotOperateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $schedule_not_operates = ScheduleNotOperate::all();
        return view('schedule_not_operate.index', compact('schedule_not_operates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $routes = Route::all();
        return view('schedule_not_operate.create', compact('routes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateScheduleNotOperateRequest $request)
    {
        foreach ($request->schedule_at as $value) {
            ScheduleNotOperate::create([
                'route_id' => $request->route_id,
                'note' => $request->note,
                'schedule_at' => $value
            ]);
        }
        session()->flash('success', 'Jadwal Libur Berhasil Ditambahkan');
        return view('schedule_not_operate.index');
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
