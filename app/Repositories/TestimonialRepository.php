<?php

namespace App\Repositories;
use App\Models\Testimonial;
class TestimonialRepository {

    public static function getAll()
    {
        return Testimonial::whereIsShow(true)->orderBy('id', 'desc')->get();
    }
}
        