<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Outcome;
class OutcomeController extends Controller
{
    public function index(Request $request)
    {
        $outcome = Outcome::paginate(10);
        return view('outcome.index', compact('outcome'));
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'route_id'      => 'integer|exists:route,id',
            'reported_at'   => 'integer|date',
            'outcome_detail'=> 'required|array',
            'outcome_detail.*.name' => 'required|string',
            'outcome_detail.*.value'    => 'required|integer',
        ]);

        $outcome = Outcome::create($data);
        foreach ($data['outcome_detail'] as $key => $value) {
            unset($data['route_id'], $data['reported_at']);
            $data['outcome_id'] = $outcome->id;
            OutcomeDetail::create($data);
        }

        return redirect('outcome')->with('success', 'Data berhasil di tambahkan');
    }

    public function edit($id)
    {
        $outcome = Outcome::with('outcome_detail')->find($id);
        return view('outcome.edit', compact('outcome'));
    }
}
