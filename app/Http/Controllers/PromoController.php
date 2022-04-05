<?php

namespace App\Http\Controllers;

use App\Models\Promo;
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
        if(empty($data['user_id'])){
            $data['is_public'] = true;
            unset($data['user_id']);
        }
        if(empty($data['start_at']) && empty($data['end_at'])){
            $data['is_scheduless'] = true;
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
        
        $data = $request->all();
        if(empty($data['quota'])){
            $data['is_quotaless'] = true;
        }
        if(empty($data['user_id'])){
            $data['is_public'] = true;
            unset($data['user_id']);
        }
        if(empty($data['start_at']) && empty($data['end_at'])){
            $data['is_scheduless'] = true;
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
}
