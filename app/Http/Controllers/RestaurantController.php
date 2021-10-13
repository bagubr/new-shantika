<?php

namespace App\Http\Controllers;

use App\Http\Requests\Restaurant\CreateRestaurantRequest;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $restaurants = Restaurant::all();
        return view('restaurant.index', compact('restaurants'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('restaurant.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRestaurantRequest $request)
    {
        $data = $request->all();
        if ($request->hasFile('image')) {
            $image = $request->image->store('image', 'public');
            $data['image'] = $image;
        };

        $number = $request->phone;
        $country_code = '62';
        $isZero = substr($number, 0, 1);
        if ($isZero == '0') {
            $new_number = substr_replace($number, '+' . $country_code, 0, ($number[0] == '0'));
            $data['phone'] = $new_number;
        } else {
            $data['phone'] = $number;
        }

        Restaurant::create($data);
        session()->flash('success', 'Restoran Berhasil Ditambahkan');
        return redirect(route('restaurant.index'));
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
    public function edit(Restaurant $restaurant)
    {
        return view('restaurant.create', compact('restaurant'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateRestaurantRequest $request, Restaurant $restaurant)
    {
        $data = $request->all();
        $number = $request->phone;
        $country_code = '62';
        $isZero = substr($number, 0, 1);
        if ($isZero == '0') {
            $new_number = substr_replace($number, '+' . $country_code, 0, ($number[0] == '0'));
            $data['phone'] = $new_number;
        } else {
            $data['phone'] = $number;
        }
        if ($request->hasFile('image')) {
            $image = $request->image->store('image', 'public');
            $restaurant->deleteImage();
            $data['image'] = $image;
        };

        $restaurant->update($data);
        session()->flash('success', 'Restoran Berhasil Diperbarui');
        return redirect(route('restaurant.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Restaurant $restaurant)
    {
        $restaurant->deleteImage();
        $restaurant->delete();

        session()->flash('success', 'Restoran Berhasil Dihapus');
        return back();
    }
}
