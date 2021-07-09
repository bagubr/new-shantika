<?php

namespace App\Http\Controllers;

use App\Http\Requests\PrivacyPolicy\CreatePrivacyPolicyRequest;
use App\Http\Requests\PrivacyPolicy\UpdatePrivacyPolicy;
use App\Models\PrivacyPolicy;
use App\Repositories\PrivacyPolicyRepository;
use Illuminate\Http\Request;

class PrivacyPolicyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $privacy_policies = PrivacyPolicyRepository::all();
        return view('privacy_policy.index', compact('privacy_policies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('privacy_policy.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePrivacyPolicyRequest $request)
    {
        $data = $request->all();
        PrivacyPolicy::create($data);
        session()->flash('success', 'Privacy Policy Created Successfully');
        return redirect(route('privacy_policy.index'));
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
    public function edit(PrivacyPolicy $privacy_policy)
    {
        return view('privacy_policy.create', compact('privacy_policy'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePrivacyPolicy $request, PrivacyPolicy $privacy_policy)
    {
        $data = $request->all();
        $privacy_policy->update($data);
        session()->flash('success', 'Privacy Policy Updated Successfully');
        return redirect(route('privacy_policy.index'));
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
