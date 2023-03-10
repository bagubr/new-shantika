<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerMenu\CreateCustomerMenuRequest;
use App\Http\Requests\CustomerMenu\UpdateCustomerMenuRequest;
use App\Models\CustomerMenu;
use CreateCustomerMenus;
use Illuminate\Http\Request;

class CustomerMenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customer_menus = CustomerMenu::all();
        return view('customer_menu.index', compact('customer_menus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // return view('customer_menu.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCustomerMenuRequest $request)
    {
        $data = $request->all();
        $data['icon'] = $request->icon->store('icon', 'public');

        CustomerMenu::create($data);
        session()->flash('success', 'Menu Pengguna Berhasil Ditambahkan');
        return redirect(route('customer_menu.index'));
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
    public function edit(CustomerMenu $customer_menu)
    {
        return view('customer_menu.create', compact('customer_menu'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCustomerMenuRequest $request, CustomerMenu $customer_menu)
    {
        $data = $request->only(['name', 'order', 'value']);
        if ($request->hasFile('icon')) {
            $icon = $request->icon->store('icon', 'public');
            $customer_menu->deleteImage();
            $data['icon'] = $icon;
        }
        $customer_menu->update($data);
        session()->flash('success', 'Menu Pengguna Berhasil Diperbarui');
        return redirect(route('customer_menu.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerMenu $customer_menu)
    {
        $customer_menu->deleteImage();
        $customer_menu->delete();
        session()->flash('success', 'Menu Pengguna Berhasil Dihapus');
        return redirect(route('customer_menu.index'));
    }
}
