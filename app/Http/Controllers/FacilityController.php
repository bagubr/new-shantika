<?php

namespace App\Http\Controllers;

use App\Http\Requests\Facility\UpdateFacilityRequest;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class FacilityController extends Controller
{
    public function index()
    {
        $facility = Facility::all();
        return view('facility.index', ['facilities' => $facility]);
    }

    public function create()
    {
        return view('facility.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'image' => 'nullable|image|max:2048'
        ]);
        $data = $request->all();
        $data['image'] = $request->image->store('facility_image', 'public');
        Facility::create($data);
        session()->flash('success', 'Fasilitas Berhasil Ditambahkan');
        return redirect(route('facility.index'));
    }

    public function edit(Request $request, $id)
    {
        return view('facility.create', ['facility' => Facility::find($id)]);
    }

    public function update(UpdateFacilityRequest $request, Facility $facility)
    {
        $data = $request->only(['name']);
        if ($request->hasFile('image')) {
            $image = $request->image->store('facility_image', 'public');
            $facility->deleteImage();
            $data['image'] = $image;
        };
        $facility->update($data);
        session()->flash('success', 'Fasilitas Berhasil Ditambahkan');
        return redirect(route('facility.index'));
    }

    public function destroy(Facility $facility)
    {
        $facility->deleteImage();
        $facility->delete();
        session()->flash('success', 'Fasilitas Berhasil Dihapus');
        return redirect(route('facility.index'));
    }
}
