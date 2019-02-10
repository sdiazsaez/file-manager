<?php

namespace Larangular\FileManager\Http\Controllers\FileManager;

use Illuminate\Http\UploadedFile;
use Larangular\FileManager\Models\FileManager;

class FileManagerController {

    public function __construct() {
    }

    public function uploadFile(UploadedFile $file, ?string $disk = null, ?string $path = null) {
        if(is_null($disk)) {
            $disk = config('file-manager.disk_name');
        }

        if(is_null($path)) {
            $path = '/';
        }

        $storedFile = $file->store($path, $disk);

        return [
            'disk' => $disk,
            'path' => $path,
            'name' => $file->hashName(),
            'original_name' => $file->getClientOriginalName(),
            'extension' => $file->extension(),
            'type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
        ];
    }

}
