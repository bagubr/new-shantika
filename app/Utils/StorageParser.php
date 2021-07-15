<?php

namespace App\Utils;

trait StorageParser {
    protected function appendPath($path) {
        return $path ? env('STORAGE_URL').'/'.$path : null;
    }
}