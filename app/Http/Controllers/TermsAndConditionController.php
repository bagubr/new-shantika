<?php

namespace App\Http\Controllers;

use App\Http\Requests\TermsCondition\CreateTermsConditionRequest;
use App\Models\TermAndCondition;
use Illuminate\Http\Request;

class TermsAndConditionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $terms_condition = TermAndCondition::where('id', '1')->first();
        return view('terms_condition.create', compact('terms_condition'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('terms_condition.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateTermsConditionRequest $request)
    {
        $data = $request->all();
        TermAndCondition::create($data);
        session()->flash('success', 'Syarat Dan Ketentuan Berhasil Ditambahkan');
        return redirect(route('terms_condition.index'));
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
    public function edit(TermAndCondition $terms_condition)
    {
        return view('terms_condition.create', compact('terms_condition'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateTermsConditionRequest $request, TermAndCondition $terms_condition)
    {
        $data = $request->all();
        $terms_condition->update($data);
        session()->flash('success', 'Syarat Dan Ketentuan Berhasil Dirubah');
        return redirect(route('terms_condition.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TermAndCondition $terms_condition)
    {
        $terms_condition->delete();
        session()->flash('success', 'Syarat Dan Ketentuan Berhasil Dihapus');
        return redirect(route('terms_condition.index'));
    }
}
