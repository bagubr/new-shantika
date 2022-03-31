<?php

namespace App\Http\Controllers;

use App\Http\Requests\Souvenir\CreateSouvenirRequest;
use App\Models\Souvenir;
use Doctrine\DBAL\Query\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SouvenirController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Souvenir::paginate(10);
        return view('souvenir.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('souvenir.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $data = $request->all();
        Validator::make($data, [
            'name' => 'required|string',
            'description' => 'required|string',
            'point' => 'required|numeric',
            'quantity' => 'required|numeric',
            'image_name' => 'required|mimes:png,jpeg,jpg|size:1024'
        ]);
        // dd($data);
        if ($request->hasFile('image_name')) {
            $data['image_name'] = $request->image_name->store('souvenir', 'public');
        }

        try{
            $create = Souvenir::create($data);
            if($create){
                return redirect()->route('souvenir.index');
            }
        }catch(QueryException){
            return redirect()->route('souvenir.index');
        }
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
        $data = Souvenir::whereId($id)->first();
        return view('souvenir.edit', compact('data'));
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
        $data = $request->all();
        $ExistingData = Souvenir::whereId($id)->first();
        Validator::make($data, [
            'name' => 'required|string',
            'description' => 'required|string',
            'point' => 'required|numeric',
            'quantity' => 'required|numeric',
            'image_name' => 'mimes:png,jpeg,jpg|size:1024'
        ]);
        // dd($data);
        if ($request->hasFile('image_name')) {
            $ExistingData->deleteImage();
            $data['image_name'] = $request->image_name->store('souvenir', 'public');
        }

        try{
            $ExistingData->update($data);
            if($ExistingData){
                return redirect()->route('souvenir.index');
            }
        }catch(QueryException){
            return redirect()->route('souvenir.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $ExistingData = Souvenir::whereId($id)->first();
            $ExistingData->delete();
            return redirect()->route('souvenir.index');
        }catch(QueryException){
            return redirect()->route('souvenir.index');
        }
    }
}
