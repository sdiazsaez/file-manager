<?php

namespace Larangular\FileManager\Http\Controllers\FileManager;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Larangular\FileManager\Facades\FileManagerController;
use Larangular\FileManager\Http\Resources\FileManagerResource;
use Larangular\FileManager\Models\FileManager;
use Larangular\RoutingController\{Contracts\IGatewayModel, Controller};


class Gateway extends Controller implements IGatewayModel {

    public function model() {
        return FileManager::class;
    }

    public function save($data) {
        if (!$this->isEditing($data)) {
            if (!array_key_exists('file', $data)) {
                return;
            }

            $fileResponse = FileManagerController::uploadFile($data['file'], @$data['disk'], @$data['path']);
            $data = array_merge($fileResponse, $data);
        } else {
            $data = [
                'id'          => $data['id'],
                'description' => @$data['description'],
            ];
        }

        return parent::save($data);
    }

    public function showFile(FileManager $fileManager) {
        $filePath = $fileManager->path . DIRECTORY_SEPARATOR . $fileManager->name;
        $storage = Storage::disk($fileManager->disk)
                          ->path($filePath);
        return response()->file($storage);
        //return Image::make()->response('png');
    }
}
