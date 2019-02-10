<?php

namespace Larangular\FileManager\Http\Controllers\FileManager;

use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Larangular\FileManager\Models\FileManager;
use Larangular\FileManager\Http\Resources\FileManagerResource;
use Larangular\FileManager\Facades\FileManagerController;
use Larangular\RoutingController\{Controller,
    Contracts\HasPagination,
    Contracts\HasResource,
    Contracts\IGatewayModel,
    Contracts\RecursiveStoreable,
    RecursiveStore\RecursiveOption};


class Gateway extends Controller implements IGatewayModel, HasResource {

    public function model() {
        return FileManager::class;
    }

    public function resource() {
        return FileManagerResource::class;
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
        $storage = Storage::disk($fileManager->disk)->path($filePath);
        return response()->file($storage);
        //return Image::make()->response('png');
    }
}
