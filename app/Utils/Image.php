<?php

namespace App\Utils;

use Illuminate\Http\UploadedFile;

class Image {
    public static function uploadFile(&$file = null, $path = '', $visibility = 'public') {
        if(@$file instanceof UploadedFile) {
            $file = $file->store($path, $visibility);
        } else {
            unset($file);
        }
    }   
}