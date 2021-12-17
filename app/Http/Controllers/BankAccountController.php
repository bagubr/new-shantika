<?php

namespace App\Http\Controllers;

use App\Http\Requests\BankAccount\CreateBankAccountRequest;
use App\Http\Requests\BankAccount\UpdateBankAccountRequest;
use App\Models\BankAccount;
use Illuminate\Http\Request;

class BankAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bank_accounts = BankAccount::all();
        return view('bank_account.index', compact('bank_accounts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('bank_account.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateBankAccountRequest $request)
    {
        $data = $request->all();
        $data['image'] = $request->image->store('bank', 'public');
        BankAccount::create($data);

        session()->flash('success', 'Data Bank Berhasil Ditambahkan');
        return redirect(route('bank_account.index'));
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
    public function edit(BankAccount $bank_account)
    {
        return view('bank_account.create', compact('bank_account'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBankAccountRequest $request, BankAccount $bank_account)
    {
        $data = $request->all();
        if ($request->hasFile('image')) {
            $image = $request->image->store('bank', 'public');
            $bank_account->deleteImage();
            $data['image'] = $image;
        }
        $bank_account->update($data);
        session()->flash('success', 'Data Bank Berhasil Dirubah');
        return redirect(route('bank_account.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(BankAccount $bank_account)
    {
        // $bank_account->deleteImage();
        $bank_account->delete();
        session()->flash('success', 'Data Bank Berhasil Dihapus');
        return back();
    }
}
