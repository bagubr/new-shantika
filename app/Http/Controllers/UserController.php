<?php

namespace App\Http\Controllers;

use App\Events\SendingNotification;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\Agency;
use App\Models\Notification;
use App\Models\Order;
use App\Models\User;
use App\Models\UserAgent;
use App\Repositories\CityRepository;
use App\Utils\NotificationMessage;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::whereDoesntHave('agencies')->get();
        $statuses = Agency::status();
        return view('user.index', compact('users', 'statuses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $genders = ['Male', 'Female'];
        return view('user.create', compact('genders'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUserRequest $request)
    {
        $data = $request->except(['agency_id']);
        $number = $request->phone;
        $country_code = '62';
        $isZero = substr($number, 0, 1);
        if ($isZero == '0') {
            $new_number = substr_replace($number, '+' . $country_code, 0, ($number[0] == '0'));
            $data['phone'] = $new_number;
        } else {
            $data['phone'] = $number;
        }
        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->avatar->store('avatar', 'public');
        }
        User::create($data);
        session()->flash('success', 'User Berhasil Ditambahkan');
        return redirect(route('user.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $orders = Order::where('user_id', $user->id)->get();
        return view('user.show', compact('user', 'orders'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $genders = ['Male', 'Female'];
        return view('user.create', compact('user', 'genders'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->only(['name', 'phone', 'email', 'birth_place', 'birth', 'address', 'gender']);
        $number = $request->phone;
        $country_code = '62';
        $isZero = substr($number, 0, 1);
        if ($isZero == '0') {
            $new_number = substr_replace($number, '+' . $country_code, 0, ($number[0] == '0'));
            $data['phone'] = $new_number;
        } else {
            $data['phone'] = $number;
        }
        if ($request->hasFile('avatar')) {
            $avatar = $request->avatar->store('avatar', 'public');
            $user->deleteAvatar();
            $data['avatar'] = $avatar;
        };
        $user->update($data);
        session()->flash('success', 'User Berhasil Diperbarui');
        return redirect(route('user.index'));
    }
    public function update_status(Request $request, User $user)
    {
        $user->update([
            'is_active' => $request->is_active,
        ]);
        
        if($request->is_active) {
            $message = NotificationMessage::activateAccount($user->name);
        } else {
            $message = NotificationMessage::deactivateAccount($user->name);
        }
        $notification = Notification::build($message[0], $message[1], Notification::TYPE2, $user->id, $user->id);
        SendingNotification::dispatch($notification, $user->fcm_token, false);

        session()->flash('success', 'User Status Berhasil Diubah');
        return redirect(route('user.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->deleteAvatar();
        $user->delete();
        session()->flash('success', 'User Berhasil Dihapus');
        return redirect(route('user.index'));
    }
}
