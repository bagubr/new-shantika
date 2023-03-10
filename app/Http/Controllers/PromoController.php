<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use App\Models\PromoHistory;
use App\Models\User;
use App\Services\PromoService;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function index()
    {
        $promos = Promo::all();
        return view('promo.index', compact('promos'));
    }

    public function create()
    {
        $users = User::pluck('name', 'id');
        return view('promo.create', compact('users'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        if(empty($data['quota'])){
            $data['is_quotaless'] = true;
        }
        if(empty($data['user_id']) || !$data['user_id']){
            $data['is_public'] = true;
            unset($data['user_id']);
        }
        PromoService::create($data);
        session()->flash('success', 'Promo Berhasil Ditambahkan');
        return redirect(route('promo.index'));
    }

    public function edit(Promo $promo)
    {
        $users = User::pluck('name', 'id');
        return view('promo.create', compact('users', 'promo'));
    }

    public function update(Request $request, Promo $promo)
    {
        if ($request->hasFile('image')) {
            $image = $request->image->store('promo', 'public');
            $promo->deleteImage();
            $data['image'] = $image;
        };
        $data = $request->all();
        if(empty($data['quota'])){
            $data['is_quotaless'] = true;
        }
        if(empty($data['user_id'])){
            $data['is_public'] = true;
            unset($data['user_id']);
        }
        if(@$data['is_public']){
            $data['user_id'] = null;
        }else{
            $data['is_public'] = false;
        }
        $promo->update($data);
        session()->flash('success', 'Promo Berhasil Diubah');
        return redirect(route('promo.index'));
    }

    public function destroy(Promo $promo)
    {
        $promo->delete();
        session()->flash('success', 'Promo Berhasil Dihapus');
        return redirect(route('promo.index'));
    }

    public function show($id)
    {
        $promo_histories = PromoHistory::wherePromoId($id)->orderBy('id', 'desc')->paginate(10)->withQueryString();
        $nominal = PromoHistory::has('order')->wherePromoId($id)->get()->sum('order.nominal_discount');
        return view('promo.history', compact('promo_histories', 'nominal'));
    }
}
