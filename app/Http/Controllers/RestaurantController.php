<?php

namespace App\Http\Controllers;

use App\Http\Requests\Restaurant\AssignRestaurantUserRequest;
use App\Http\Requests\Restaurant\CreateRestaurantRequest;
<<<<<<< HEAD
=======
use App\Http\Requests\Restaurant\UpdateRestaurantRequest;
>>>>>>> rilisv1
use App\Models\Admin;
use App\Models\FoodRedeemHistory;
use App\Models\Restaurant;
use App\Models\RestaurantAdmin;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
<<<<<<< HEAD
=======
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
>>>>>>> rilisv1

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
<<<<<<< HEAD

        Restaurant::create($data);
=======
        $data_admin['name'] = $data['username'];
        $data_admin['password'] = Hash::make($data['password']);
        $data_admin['email'] = $data['email'];
        DB::beginTransaction();
        try {
            $admin = Admin::create($data_admin);
            $admin->assignRole('restaurant');
            $restaurant = Restaurant::create($data);
    
            RestaurantAdmin::create([
                'admin_id' => $admin->id,
                'restaurant_id' => $restaurant->id,
                'phone' => $data['phone']
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('error', 'Restoran Gagal Ditambahkan');
        }
        
>>>>>>> rilisv1
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
<<<<<<< HEAD
        })->get();
=======
        })->orderBy('id', 'desc')->get();
>>>>>>> rilisv1
        return view('restaurant.show', compact('restaurant', 'admins', 'restaurant_admin'));
    }

    public function show_restaurant_detail()
    {
        $user = Auth::user()->restaurant_admin;
<<<<<<< HEAD
        $restaurant = Restaurant::where('id', $user->restaurant_id)->first();
        return view('restaurant.show_user', compact('restaurant'));
    }
    public function history_restaurant()
    {
        $food_reddem_histories = FoodRedeemHistory::all();
=======
        if(isset($user->restaurant_id)){
            $restaurant = Restaurant::findOrFail($user->restaurant_id);
            return view('restaurant.show_user', compact('restaurant'));
        }
    }
    public function history_restaurant()
    {
        $area_id = Auth::user()->area_id;
        $food_reddem_histories = FoodRedeemHistory::
        when($area_id, function ($query) use ($area_id)
        {
            $query->whereHas('order_detail.order.fleet_route.route.checkpoints.agency.city', function ($query) use ($area_id)
            {
                $query->where('area_id', $area_id);
            });
        })
        ->get();
>>>>>>> rilisv1
        $restaurants = Restaurant::all();
        return view('restaurant.history', compact('food_reddem_histories', 'restaurants'));
    }
    public function history_restaurant_search(Request $request)
    {
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
    public function history_restaurant_detail_search(Request $request)
    {
        $user = Auth::user()->restaurant_admin;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $food_reddem_histories = FoodRedeemHistory::query();

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
        $food_reddem_histories   = $food_reddem_histories->where('restaurant_id', $user->restaurant_id)->get();
        if (!$food_reddem_histories->isEmpty()) {
            session()->flash('success', 'Data Berhasil Ditemukan');
        } else {
            session()->flash('error', 'Tidak Ada Data Ditemukan');
        }
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
<<<<<<< HEAD
=======
        $restaurant->username = $restaurant->admin[0]?->name??'';
        $restaurant->email = $restaurant->admin[0]?->email??'';
        $restaurant->admin_id = $restaurant->admin[0]?->id??0;
        $restaurant->restaurant_id = $restaurant->id;
>>>>>>> rilisv1
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
<<<<<<< HEAD
=======
        $admin = Admin::create($data);
        $admin->assignRole('restaurant');
        $data['admin_id'] = $admin->id;
>>>>>>> rilisv1
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
<<<<<<< HEAD
    public function update(CreateRestaurantRequest $request, Restaurant $restaurant)
=======
    public function update(UpdateRestaurantRequest $request, Restaurant $restaurant)
>>>>>>> rilisv1
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
<<<<<<< HEAD

        $restaurant->update($data);
=======
        if($request->password){
            $password = Hash::make($request->password);
        }
        DB::beginTransaction();
        try {
            $admin = Admin::updateOrCreate([
                'id' => $request->admin_id
            ], [
                'name' => $request->username,
                'email' => $request->email,
                'password' => $password
            ]);
            $admin->syncRoles('restaurant');
            // dd($admin);
            RestaurantAdmin::updateOrCreate([
                'admin_id' => $admin->id,
                'restaurant_id' => $request->restaurant_id,
            ], [
                'phone' => $data['phone']
            ]);
            $restaurant->update($data);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('error', 'Data gagal di perbaharui');
            //throw $th;
        }
        
>>>>>>> rilisv1
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
