<?php

namespace App\Http\Controllers;

use App\Http\Requests\Layout\CreateLayoutRequest;
use App\Http\Requests\Layout\UpdateLayoutRequest;
use App\Models\Layout;
use App\Models\LayoutChair;
use App\Repositories\LayoutRepository;
use Illuminate\Http\Request;
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
        throw new NotFoundHttpException();
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
        $layout = Layout::find($id);
        $layout->update($request->except(['chair_indexes']));
        $layout->refresh();
        $layout->chairs()->delete();

        $chairs = [];
        foreach($request->chair_indexes as $i) {
            $chairs[] = LayoutChair::create(array_merge($i, [
                'layout_id'=>$layout->id
            ]));
        }
        return response([
            $layout,  $chairs
        ]);
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
