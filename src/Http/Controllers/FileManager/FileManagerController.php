<?php

namespace Larangular\FileManager\Http\Controllers\FileManager;

use Illuminate\Http\UploadedFile;

class FileManagerController {

    public function __construct() {
    }

    public function uploadFile(UploadedFile $file, ?string $disk = null, ?string $path = null) {
        if (is_null($disk)) {
            $disk = config('file-manager.disk_name');
        }

        if (is_null($path)) {
            $path = '/';
        }

        $storedFile = $file->store($path, $disk);

        return [
            'disk'          => $disk,
            'path'          => $path,
            'name'          => $file->hashName(),
            'original_name' => $file->getClientOriginalName(),
            'extension'     => $this->getExtension($file),
            'type'          => $this->getMimeType($file),
            'size'          => $file->getSize(),
        ];
    }

    private function getExtension(UploadedFile $file): string {
        $ext = $file->extension();
        return is_null($ext)
            ? $file->getClientOriginalExtension()
            : $ext;
    }

    private function getMimeType(UploadedFile $file): string {
        $type = $file->getMimeType();
        return is_null($type)
            ? $file->getClientMimeType()
            : $type;
    }

}
