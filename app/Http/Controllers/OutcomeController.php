<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Outcome;
use App\Models\OutcomeType;
use App\Models\OutcomeDetail;
use App\Repositories\OrderRepository;
use App\Models\Order;
use App\Models\Route;
class OutcomeController extends Controller
{
    public function index(Request $request)
    {
        $outcomes = Outcome::paginate(10);
        return view('outcome.index', compact('outcomes'));
    }

    public function create()
    {
        $orders = [];
        $routes = Route::all();
        return view('outcome.create', compact('routes', 'orders'));
    }

    public function createType()
    {
        $outcome_types = OutcomeType::orderBy('id')->get();
        return view('outcome.outcome_type', compact('outcome_types'));
    }

    public function storeType(Request $request)
    {   
        OutcomeType::create([
            'name' => $request->name
        ]);
        return redirect()->back()->with('success', 'Data berhasil di tambahkan');
    }
    
    public function search(Request $request)
    {
        $orders = OrderRepository::getAtDateAndRoute($request->reported_at, $request->route_id);
        $routes = Route::all();
        $route_id = $request->route_id??'';
        $reported_at = $request->reported_at??'';
        return view('outcome.create', compact('routes', 'orders', 'route_id', 'reported_at'));
    }

    public function store(Request $request)
    {
        if($request->route_id != 'WITH_TYPE' && $request->route_id){
            $exist = Outcome::whereDate('reported_at', $request->reported_at)->whereRouteId($request->route_id)->first();
            if($exist){
                return redirect('outcome')->with('error', 'Data sudah di tambahkan');
            }
            $data = $this->validate($request, [
                'route_id'      => 'required|integer|exists:routes,id',
            ]);
            $order_price_distribution = OrderRepository::getAtDateAndRoute($request->reported_at, $request->route_id);
            $data['order_price_distribution_id'] = $order_price_distribution?->pluck('id')??[];
        }else{
            $this->validate($request, [
                'outcome_type_id'      => 'required|integer|exists:outcome_types,id',
            ]);
            $data['outcome_type_id'] = $request->outcome_type_id;
        }
        $data += $this->validate($request, [
            'reported_at'   => 'required|date',
            'name'          => 'required|array',
            'amount'        => 'required|array',
            'name.*'        => 'required|string',
            'amount.*'      => 'required|integer',
        ]);
        foreach ($data['name'] as $key => $value) {
            $data['outcome_details'][] = [
                'name' => $value,
                'amount' => $request->amount[$key],
            ];
        }
        \DB::beginTransaction();
        try {
            $outcome = Outcome::create($data);
            foreach ($data['outcome_details'] as $key => $value) {
                unset($data['route_id'], $data['reported_at']);
                $value['outcome_id'] = $outcome->id;
                OutcomeDetail::create($value);
            }
        } catch (\Throwable $th) {
            \DB::rollback();
            throw $th;
            return redirect('outcome')->with('error', 'Data gagal di tambahkan');
        }
        \DB::commit();

        return redirect('outcome')->with('success', 'Data berhasil di tambahkan');
    }

    public function edit($id)
    {
        $outcome = Outcome::with('outcome_detail')->find($id);
        return view('outcome.edit', compact('outcome'));
    }
    
    public function show($id)
    {
        $outcome = Outcome::with('outcome_detail')->find($id);
        $orders = Order::whereIn('id', json_decode($outcome->order_price_distribution_id))->get();
        return view('outcome.show', compact('outcome', 'orders'));
    }

    public function destroyType($id)
    {
        try {
            $outcome_type = OutcomeType::find($id);
            $outcome_type->delete();
            return redirect()->back()->with('success', 'Berhasil Hapus Data');
        } catch (\Throwable $th) {
            return redirect()->back()->with('success', 'Gagal Hapus Data');
        }
    }
}
