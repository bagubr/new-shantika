<?php

namespace App\Http\Controllers;

use App\Exports\OutcomeExport;
use Illuminate\Http\Request;
use App\Models\Outcome;
use App\Models\OutcomeType;
use App\Models\OutcomeDetail;
use App\Repositories\OrderRepository;
use App\Models\Order;
use App\Models\Route;
use App\Models\Fleet;
use App\Models\FleetDetail;
use App\Models\FleetRoute;
use Maatwebsite\Excel\Facades\Excel;

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
        $fleet_details = FleetDetail::has('fleet_route')->get();
        return view('outcome.create', compact('orders', 'fleet_details'));
    }

    public function createType()
    {
        $outcome_types = OutcomeType::orderBy('id')->get();
        return view('outcome.outcome_type', compact('outcome_types'));
    }
    public function export($id)
    {
        $outcome = Outcome::find($id);
        $file_name = $outcome->fleet_detail->fleet->name.'_'.$outcome->fleet_detail->plate_number.'_'.$outcome->fleet_detail->nickname.'_'.$outcome->reported_at;
        return Excel::download(new OutcomeExport($id), $file_name.'.xlsx');
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
        $orders = OrderRepository::getAtDateAndFleetDetail($request->reported_at, $request->fleet_detail_id);
        $fleet_details = FleetDetail::has('fleet_route')->get();
        $fleet_detail_id = $request->fleet_detail_id ?? '';
        $reported_at = $request->reported_at ?? '';
        return view('outcome.create', compact('fleet_details', 'orders', 'fleet_detail_id', 'reported_at'));
    }

    public function store(Request $request)
    {
        if ($request->fleet_detail_id != 'WITH_TYPE' && $request->fleet_detail_id) {
            $exist = Outcome::whereDate('reported_at', $request->reported_at)->whereFleetDetailId($request->fleet_detail_id)->first();
            $order_price_distribution = OrderRepository::getAtDateAndFleetDetail($request->reported_at, $request->fleet_detail_id);
            if ($exist) {
                $after = count($order_price_distribution?->pluck('id'));
                $before = count(json_decode($exist->order_price_distribution_id));
                if ($before < $after) {
                    $exist->update([
                        'order_price_distribution_id' => $order_price_distribution?->pluck('id') ?? [],
                    ]);
                    return redirect('outcome')->with('success', 'Data sudah di update');
                }
                return redirect('outcome')->with('error', 'Data sudah di tambahkan');
            }
            $data = $this->validate($request, [
                'fleet_detail_id'      => 'required|integer|exists:fleet_details,id',
            ]);
            $data['order_price_distribution_id'] = $order_price_distribution?->pluck('id') ?? [];
        } else {
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
                unset($data['fleet_detail_id'], $data['reported_at']);
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
        return view('outcome.show', compact('outcome', 'orders', 'id'));
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

    public function destroy($id)
    {
        $outcome = Outcome::find($id);
        $outcome->delete();
        try {
            return redirect()->back()->with('success', 'Berhasil Hapus Data');
        } catch (\Throwable $th) {
            return redirect()->back()->with('success', 'Gagal Hapus Data');
        }
    }

    // public function export($id)
    // {
    //     return Excel::download(new OutcomeFromViewExport($id), 'pengeluran.xlsx');
    // }
}
