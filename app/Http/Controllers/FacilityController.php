<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class FacilityController extends Controller
{
    public function index(Request $request) {
        $facility = Facility::all();
        return view('facility.index', ['facilities'=>$facility]);
    } 
    
    public function create(Request $request) {
        return view('facility.create');
    } 
    
    public function store(Request $request) {
        $data = $request->validate([
            'name'=>'required',
            'image'=>'nullable'
        ]);
        if(@$data['image'] instanceof UploadedFile) {
            $data['image'] = $data['image']->store('facility_image', 'public');
        }
        Facility::create($data);
        return back();
    } 
    
    public function edit(Request $request, $id) {
        return view('facility.create', ['facility'=>Facility::find($id)]);
    } 
    
    public function update(Request $request, $id) {
        $data = $request->only(['name', 'image']);
        $facility = Facility::find($id);
        if(@$data['image'] instanceof UploadedFile) {
            $data['image'] = $data['image']->store('facility_image', 'public');
        } else {
            unset($data['image']);
        }
        $facility->update($data);
        return back();
    } 
    
    public function destroy(Request $request, $id) {
        Facility::find($id)->delete();

        return back();
    } 
        
}
