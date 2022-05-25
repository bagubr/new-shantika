<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\Membership;
use App\Models\SouvenirRedeem;
use App\Services\MembershipService;
use Doctrine\DBAL\Query\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SouvenirRedeemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $souvenir_redeems = SouvenirRedeem::with('membership.user')->paginate(10);
        return view('souvenir_redeem.index', compact('souvenir_redeems'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SouvenirRedeem  $souvenirRedeem
     * @return \Illuminate\Http\Response
     */
    public function show(SouvenirRedeem $souvenirRedeem)
    {
        SouvenirRedeem::whereHas('membership', function($q){
            $q->where('id', '!=', 0);
        })->get();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SouvenirRedeem  $souvenirRedeem
     * @return \Illuminate\Http\Response
     */
    public function edit(SouvenirRedeem $souvenirRedeem)
    {
        $data = $souvenirRedeem;
        $agencies = Agency::select('id', 'name')->all();
        return view('souvenir_redeem.edit', compact('data', 'agencies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SouvenirRedeem  $souvenirRedeem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SouvenirRedeem $souvenirRedeem)
    {
        $data = $request->all();
        $rules = [
            'status' => 'required',
            'note' => 'sometimes'
        ];
        Validator::make($data, $rules);
        if($data['status'] == 'DECLINED'){
            MembershipService::increment(Membership::find($souvenirRedeem->membership_id), $souvenirRedeem->point_used, 'Pengembalian Point');
        }
        try{
            $souvenirRedeem->update($data);
            return redirect()->route('souvenir_redeem.index');
        }catch(QueryException $e){
            return redirect()->route('souvenir_redeem.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SouvenirRedeem  $souvenirRedeem
     * @return \Illuminate\Http\Response
     */
    public function destroy(SouvenirRedeem $souvenirRedeem)
    {
        //
    }
}