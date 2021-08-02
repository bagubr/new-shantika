<?php

namespace App\Http\Controllers;

use App\Http\Requests\Payment\UpdatePaymentRequest;
use App\Models\Payment;
use App\Repositories\PaymentTypeRepository;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payments = Payment::all();
        return view('payment.index', compact('payments'));
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
    public function edit(Payment $payment)
    {
        $payment_types = PaymentTypeRepository::all();
        $test = strtotime($payment->paid_at);
        $time = date('Y-m-d', $test);
        $statuses = ['PENDING', 'EXPIRED', 'PAID', 'CANCELED', 'EXCHANGED', 'WAITING_CONFIRMATION', 'DECLINED', 'FINISHED'];
        return view('payment.create', compact('payment', 'payment_types', 'statuses', 'time'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePaymentRequest $request, Payment $payment)
    {
        $data = $request->only('payment_type_id', 'status', 'proof_decline_reason', 'paid_at');
        if ($request->hasFile('proof')) {
            $proof = $request->proof->store('proof', 'public');
            $payment->deleteProof();
            $data['proof'] = $proof;
        };
        $payment->update($data);
        session()->flash('success', 'Pembayaran Berhasil Diperbarui');
        return redirect(route('payment.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment)
    {
        $payment->deleteProof();
        $payment->delete();
        session()->flash('success', 'Pembayaran Berhasil Dihapus');
        return redirect(route('payment.index'));
    }
}
