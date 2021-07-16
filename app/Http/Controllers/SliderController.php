<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSliderRequest;
use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sliders = Slider::all();
        return view('slider.index', compact('sliders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('slider.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateSliderRequest $request)
    {
        $data = $request->all();
        $data['image'] = $request->image->store('slider_image', 'public');

        Slider::create($data);
        session()->flash('success', 'Slider Berhasil Ditambahkan');
        return redirect(route('slider.index'));
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
    public function edit(Slider $slider)
    {
        return view('slider.create', compact('slider'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateSliderRequest $request, Slider $slider)
    {
        $data = $request->all();
        if ($request->hasFile('image')) {
            $image = $request->image->store('slider_image', 'public');
            $slider->deleteImage();
            $data['image'] = $image;
        };
        $slider->update($data);

        session()->flash('success', 'Slider Berhasil Diperbarui');
        return redirect(route('slider.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Slider $slider)
    {
        $slider->deleteImage();
        $slider->delete();
        session()->flash('success', 'Slider Berhasil Dihapus');
        return redirect(route('slider.index'));
    }
}
