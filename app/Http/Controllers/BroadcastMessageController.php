<?php

namespace App\Http\Controllers;

use App\Events\SendingNotificationToTopic;
use App\Models\BroadcastMessage;
use App\Models\Notification;
use Illuminate\Http\Request;

class BroadcastMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $broadcast_messages = BroadcastMessage::all();
        return view('broadcast_message.index', compact('broadcast_messages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('broadcast_message.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $broadcast_message = BroadCastMessage::create($data);
        $notification = new Notification([
            'title'=>$data['title'],
            'body'=>$data['message'],
            'reference_id'=>$broadcast_message->id,
            'type'=>Notification::TYPE8
        ]);
        SendingNotificationToTopic::dispatch($notification, Notification::TOPIC1, false, [
            'reference_id'=>(string) $broadcast_message->id,
            'type'=>Notification::TYPE8
        ]);
        session()->flash('success', 'BroadCast Berhasil di sebarkan');
        return redirect(route('broadcast_message.index'));
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
