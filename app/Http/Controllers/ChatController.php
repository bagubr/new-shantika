<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateChatRequest;
use App\Models\Chat;
use App\Repositories\ChatRepository;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $chats = ChatRepository::all();
        return view('chat.index', compact('chats'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('chat.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateChatRequest $request)
    {
        $data = $request->all();
        if ($request->icon) {
            $data['icon'] = $request->icon->store('icon', 'public');
        }
        if ($request->value) {
            $data['value'] = '?text=' . $request->value;
        }
        Chat::create($data);
        session()->flash('success', 'Chat Berhasil Ditambahkan');
        return redirect(route('chat.index'));
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
    public function edit(Chat $chat)
    {
        return view('chat.create', compact('chat'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateChatRequest $request, Chat $chat)
    {
        $data = $request->only(['name', 'value', 'type', 'link']);
        if ($request->hasFile('icon')) {
            $icon = $request->icon->store('icon', 'public');
            $chat->deleteIcon();
            $data['icon'] = $icon;
        }
        if ($request->value) {
            $data['value'] = '?text=' . $request->value;
        }
        $chat->update($data);
        session()->flash('success', 'Chat Update Successfully');
        return redirect(route('chat.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Chat $chat)
    {
        $chat->deleteIcon();
        $chat->delete();
        session()->flash('success', 'Chat Delete Successfully');
        return redirect(route('chat.index'));
    }
}
