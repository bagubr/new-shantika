<?php

namespace App\Http\Controllers;

use App\Http\Requests\About\CreateAboutRequest;
use App\Http\Requests\About\UpdateAboutRequest;
use App\Models\About;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $abouts = About::all();
        return view('about.index', compact('abouts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('about.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateAboutRequest $request)
    {
        $data = $request->all();
        $data['image'] = $request->image->store('about', 'public');

        About::create($data);
        session()->flash('success', 'Tentang Kita Berhasil Ditambahkan');
        return redirect(route('about.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(About $about)
    {
        return view('about.create', compact('about'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAboutRequest $request, About $about)
    {
        $data = $request->only(['address', 'image']);
        if ($request->hasFile('image')) {
            $image = $request->image->store('about', 'public');
            $about->deleteImage();
            $data['image'] = $image;
        };
        $about->update($data);
        session()->flash('success', 'Tentang Kita Berhasil Diperbarui');
        return redirect(route('about.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(About $about)
    {
        $about->deleteImage();
        $about->delete();
        session()->flash('success', 'Tentang Kita Berhasil Diperbarui');
        return redirect(route('about.index'));
    }
}
