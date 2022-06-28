<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use App\Models\SouvenirRedeem;
=======
use App\Models\Agency;
use App\Models\Membership;
use App\Models\SouvenirRedeem;
use App\Services\MembershipService;
>>>>>>> rilisv1
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
<<<<<<< HEAD
    public function index()
    {
        $data = SouvenirRedeem::with('membership.user:id,name')->paginate(10);
        return view('souvenir_redeem.index', compact('data'));
=======
    public function index(Request $request)
    {
        $created_at = $request->created_at;
        $name = $request->name;
        $souvenir_redeems = SouvenirRedeem::with('membership.user')
        ->when($created_at, function ($query) use ($created_at)
        {
            $query->whereDate('created_at', $created_at);
        })
        ->when($name, function ($query) use ($name)
        {
            $query->whereHas('membership.user', function ($query) use ($name)
            {
                $query->where('name','ilike', "%".$name."%");
            });
        })
        ->orderBy('id', 'desc')->paginate(10)->withQueryString();
        return view('souvenir_redeem.index', compact('souvenir_redeems', 'name', 'created_at'));
>>>>>>> rilisv1
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
<<<<<<< HEAD
        return view('souvenir_redeem.edit', compact('data'));
=======
        $agencies = Agency::select('id', 'name')->get();
        return view('souvenir_redeem.edit', compact('data', 'agencies'));
>>>>>>> rilisv1
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
<<<<<<< HEAD
=======
        if($data['status'] == 'DECLINED'){
            MembershipService::increment(Membership::find($souvenirRedeem->membership_id), $souvenirRedeem->point_used, 'Pengembalian Point');
        }
>>>>>>> rilisv1
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