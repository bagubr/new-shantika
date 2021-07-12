<?php

namespace App\Repositories;

use App\Models\Layout;

class LayoutRepository {
    public static function findWithChairs($id) {
        return Layout::with('chairs')->find($id);
    }

    public static function latestWithChairs() {
        return Layout::with('chairs')->orderBy('id', 'desc')->first();
    }
}
        