<?php

namespace Larangular\FileManager\Http\Controllers\FileRelationship;

use Larangular\FileManager\Http\Controllers\FileManager\Gateway as FileManagerGateway;
use Larangular\FileManager\Models\FileRelationship;
use Larangular\RoutingController\Contracts\IGatewayModel;
use Larangular\RoutingController\Contracts\RecursiveStoreable;
use Larangular\RoutingController\Controller;
use Larangular\RoutingController\RecursiveStore\RecursiveOption;
use Msd\VehicleAssets\Models\VehicleInvoice;

class Gateway extends Controller implements IGatewayModel, RecursiveStoreable {

    public function model() {
        return FileRelationship::class;
    }

    public function recursiveOptions() {
        return [
            new RecursiveOption('file_asset_id', FileManagerGateway::class),
        ];
    }
}
