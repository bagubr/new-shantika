<?php

namespace App\Http\Controllers;

use App\Http\Requests\Layout\CreateLayoutRequest;
use App\Http\Requests\Layout\UpdateLayoutRequest;
use App\Models\Layout;
use App\Models\LayoutChair;
use App\Repositories\LayoutRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LayoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $layouts = LayoutRepository::paginateWithChairs();
        return view('layout.index', compact('layouts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $layout = LayoutRepository::latestWithChairs();
        return view('layout.edit', compact('layout'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateLayoutRequest $request)
    {
        $layout = Layout::create($request->all());
        $chairs = [];
        foreach($request->chair_indexes as $i) {
            $chairs[] = LayoutChair::create(array_merge($i, [
                'layout_id'=>$layout->id
            ]));
        }
        $request->session()->flash('success', 'Berhasil menyimpan data!');
        return response([
            $layout,  $chairs     
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {        
        $layout = LayoutRepository::findWithChairs($id);
        
        return view('layout.edit', compact('layout'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLayoutRequest $request, $id)
    {
        DB::beginTransaction();
        $layout = Layout::find($id);
        $layout->update($request->only(['id', 'name', 'note']));
        DB::commit();
        $request->session()->flash('success', 'Berhasil menyimpan data!');
        return response([
            $layout
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        try {
            Layout::find($id)->delete();
            return back()->with('success', 'Layout berhasil dihapus');
        } catch(\Exception $e) {
            return back()->with('error', 'Layout gagal dihapus');
        }
    }
}
