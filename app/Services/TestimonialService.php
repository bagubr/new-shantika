<?php

namespace App\Services;
use App\Models\Testimonial;
use App\Utils\Image;
use Illuminate\Http\UploadedFile;
class TestimonialService {
    
    public static function create($testimonial)
    {
        return $testimonial;
        $testimonial->image = $testimonial->image->store('testimonial', 'public');
        // if(@$testimonial->image instanceof UploadedFile) {
        // }
        $testimonial->save();
        return $testimonial;
    }
}
        