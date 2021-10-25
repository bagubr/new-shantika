<?php

namespace App\Http\Controllers;

use App\Http\Requests\Restaurant\AssignRestaurantUserRequest;
use App\Http\Requests\Restaurant\CreateRestaurantRequest;
use App\Models\Admin;
use App\Models\FoodRedeemHistory;
use App\Models\Restaurant;
use App\Models\RestaurantAdmin;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function show(Restaurant $restaurant)
    {
        $admins = Admin::whereHas('roles', function ($q) {
            $q->where('name', 'restaurant');
        })->whereDoesntHave('restaurant_admin')->get();
        $restaurant_admin = Admin::whereHas('restaurant_admin', function ($q) use ($restaurant) {
            $q->where('restaurant_id', $restaurant->id);
        })->get();
        return view('restaurant.show', compact('restaurant', 'admins', 'restaurant_admin'));
    }

    public function show_restaurant_detail()
    {
        $user = Auth::user()->restaurant_admin;
        $restaurant = Restaurant::where('id', $user->restaurant_id)->first();
        return view('restaurant.show_user', compact('restaurant'));
    }
    public function history_restaurant()
    {
        $food_reddem_histories = FoodRedeemHistory::all();
        $restaurants = Restaurant::all();
        return view('restaurant.history', compact('food_reddem_histories', 'restaurants'));
    }
    public function history_restaurant_search(Request $request)
    {
        $time = Carbon::now()->toDateString();
        $restaurant_id = $request->restaurant_id;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $restaurants = Restaurant::get();
        $food_reddem_histories = FoodRedeemHistory::query();

        if (!empty($restaurant_id)) {
            $food_reddem_histories = $food_reddem_histories->where('restaurant_id', $restaurant_id);
        }
        if (!empty($start_date) || !empty($end_date)) {
            if (empty($start_date)) {
                $food_reddem_histories = $food_reddem_histories->whereDate('created_at', '<=', $end_date);
            } else if (empty($end_date)) {
                $food_reddem_histories = $food_reddem_histories->whereDate('created_at', '>=', $start_date);
            } else {
                $food_reddem_histories = $food_reddem_histories->whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date);
            }
        }
        $test     = $request->flash();
        $food_reddem_histories   = $food_reddem_histories->get();
        if (!$food_reddem_histories->isEmpty()) {
            session()->flash('success', 'Data Berhasil Ditemukan');
        } else {
            session()->flash('error', 'Tidak Ada Data Ditemukan');
        }
        // $food_reddem_histories = FoodRedeemHistory::all();
        // $restaurants = Restaurant::all();
        return view('restaurant.history', compact('food_reddem_histories', 'restaurants'));
    }
    public function history_restaurant_detail()
    {
        $user = Auth::user()->restaurant_admin;
        $food_reddem_histories = FoodRedeemHistory::where('restaurant_id', $user->restaurant_id)->get();
        return view('restaurant.history_user', compact('food_reddem_histories'));
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

    public function assign_user(AssignRestaurantUserRequest $request)
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
        RestaurantAdmin::create($data);
        session()->flash('success', 'Admin Restoran Berhasil Ditambahkan');
        return back();
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

    public function destroy_admin(RestaurantAdmin $restaurant_admin)
    {
        $restaurant_admin->delete();

        session()->flash('success', 'Restoran Admin Berhasil Dihapus');
        return back();
    }
}
