<?php

namespace App\Repositories;
use App\Models\Testimonial;
class TestimonialRepository {

    public static function getAll()
    {
        return Testimonial::orderBy('id', 'desc')->get();
    }
}
        