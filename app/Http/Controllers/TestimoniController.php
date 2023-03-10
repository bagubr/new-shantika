<?php

namespace App\Http\Controllers;

use App\Http\Requests\Testimoni\CreateTestimoniRequest;
use App\Http\Requests\Testimoni\UpdateTestimoniRequest;
use App\Models\Testimonial;
use App\Models\User;
use App\Services\TestimonialService;
use Illuminate\Http\Request;

class TestimoniController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $testimonials = Testimonial::all();
        return view('testimoni.index', compact('testimonials'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::all();
        return view('testimoni.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateTestimoniRequest $request)
    {
        $data = $request->all();
        TestimonialService::create($data);
        return redirect(route('testimoni.index'))->with('success', 'Testimoni Berhasil Ditambahkan');
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
    public function edit(Testimonial $testimoni)
    {
        $users = User::all();
        return view('testimoni.create', compact('testimoni', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTestimoniRequest $request, Testimonial $testimoni)
    {
        $data = $request->except('image');
        if ($request->hasFile('image')) {
            $image = $request->image->store('testimonial', 'public');
            $testimoni->deleteImage();
            $data['image'] = $image;
        };
        $testimoni->update($data);
        session()->flash('success', 'Testimoni Berhasil Dirubah');
        return redirect(route('testimoni.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Testimonial $testimoni)
    {
        $testimoni->deleteImage();
        $testimoni->delete();
        session()->flash('success', 'Testimoni Berhasil Dihapus');
        return redirect(route('testimoni.index'));
    }
}
