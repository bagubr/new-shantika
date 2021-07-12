<?php

namespace App\Repositories;

use App\Models\Layout;

class LayoutRepository {
    public static function findWithChairs($id) {
        return Layout::with('chairs')->find($id);
    }
}
        